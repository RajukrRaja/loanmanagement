<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\EnachApproval;
use App\Models\LoanDetail;
use App\Models\EmiPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmiController extends Controller
{
    /**
     * Calculate EMI and store results in the database.
     */
    public function showEMI(Request $request)
    {
        try {
            // Log input data
            Log::debug('showEMI input', [
                'input' => $request->all(),
                'user_id' => Auth::id(),
            ]);

            // Step 1: Validate input
            $validated = $request->validate([
                'lead_id' => [
                    'required',
                    Rule::exists('leads', 'lead_id')->where('kyc_status', 'Approved'),
                ],
                'loan_type' => 'required|in:personal,home,auto,business',
                'cash_mode' => 'required|in:bank_transfer,cash,cheque,online_payment',
                'emi_date' => 'required|date',
                'disbursement_date' => 'required|date', // Changed to required
            ]);

            // Step 2: Fetch lead and enach_approval
            Log::debug('Fetching Lead and EnachApproval', ['lead_id' => $validated['lead_id']]);
            $lead = Lead::findOrFail($validated['lead_id']);
            $enachApproval = EnachApproval::where('lead_id', $validated['lead_id'])->first();

            // Step 3: Check if enach_approval exists and has valid approved_loan_amount
            if (!$enachApproval || !$enachApproval->approved_loan_amount || $enachApproval->approved_loan_amount <= 0) {
                Log::warning('Invalid or missing enach approval data', [
                    'lead_id' => $validated['lead_id'],
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'No valid approved loan amount found for this lead.',
                ], 422);
            }

            // Step 4: Validate calculation inputs
            $principal = $enachApproval->approved_loan_amount;
            $annualRate = $lead->interest_rate ?? 18;
            $months = $lead->tenure_months ?? 12;
            $method = strtolower($lead->interest_type ?? 'reduce');
            $monthlyRate = $annualRate / 1200;

            Log::debug('EMI calculation inputs', [
                'principal' => $principal,
                'annualRate' => $annualRate,
                'months' => $months,
                'method' => $method,
                'monthlyRate' => $monthlyRate,
            ]);

            // Validate loan parameters
            if (!is_numeric($principal) || $principal <= 0 ||
                !is_numeric($months) || $months <= 0 ||
                !is_numeric($annualRate) || $annualRate < 0) {
                Log::error('Invalid EMI calculation inputs', [
                    'principal' => $principal,
                    'months' => $months,
                    'annualRate' => $annualRate,
                    'lead_id' => $validated['lead_id'],
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid loan parameters for EMI calculation.',
                ], 422);
            }

            // Validate monthly rate for reducing balance method
            if ($method === 'reduce' && $monthlyRate <= 0) {
                Log::error('Invalid monthly rate for EMI calculation', [
                    'monthlyRate' => $monthlyRate,
                    'lead_id' => $validated['lead_id'],
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Interest rate must be greater than zero for EMI calculation.',
                ], 422);
            }

            // Step 5: EMI calculation
            if ($method === 'reduce') {
                $x = pow(1 + $monthlyRate, $months);
                $emi = ($principal * $monthlyRate * $x) / ($x - 1);
                $totalPayment = $emi * $months;
                $totalInterest = $totalPayment - $principal;
            } else {
                $totalInterest = $principal * ($annualRate / 100) * ($months / 12);
                $totalPayment = $principal + $totalInterest;
                $emi = $totalPayment / $months;
            }

            // Step 6: Round results
            $emi = round($emi, 2);
            $totalPayment = round($totalPayment, 2);
            $totalInterest = round($totalInterest, 2);

            // Step 7: Amortization schedule
            Log::debug('Generating amortization schedule', ['lead_id' => $validated['lead_id']]);
            $amortization = [];
            $opening = $principal;

            // Parse emi_date safely
            try {
                $emiDate = Carbon::parse($validated['emi_date']);
            } catch (\Exception $e) {
                Log::error('Invalid EMI date format', [
                    'emi_date' => $validated['emi_date'],
                    'lead_id' => $validated['lead_id'],
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid EMI date format.',
                ], 422);
            }

            // Step 8: Store in loan_details
            $loanDetail = LoanDetail::updateOrCreate(
                ['lead_id' => $validated['lead_id']],
                [
                    'disbursement_date' => $validated['disbursement_date'],
                    'approved_loan_amount' => $principal,
                    'disbursed_amount' => $principal,
                    'amount_paid' => 0,
                    'amount_unpaid' => $totalPayment,
                    'unpaid_principal' => $principal,
                    'unpaid_interest' => $totalInterest,
                    'total_penalty' => 0,
                    'paid_penalty' => 0,
                    'remaining_penalty' => 0,
                    'total_amount' => $totalPayment,
                    'interest' => $totalInterest,
                    'emi_status' => '0/' . $months . ' Paid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Step 9: Store in emi_paid
            EmiPaid::where('lead_id', $validated['lead_id'])->delete(); // Clear existing EMI records
            $amortizationRecords = [];
            for ($i = 1; $i <= $months; $i++) {
                $interest = round($opening * $monthlyRate, 2);
                $principalPart = round($emi - $interest, 2);
                $closing = round(max($opening - $principalPart, 0), 2);

                $amortizationRecords[] = [
                    'month' => $i,
                    'opening_balance' => $opening,
                    'emi' => $emi,
                    'principal' => $principalPart,
                    'interest' => $interest,
                    'closing_balance' => $closing,
                    'expiry' => $emiDate->copy()->addMonths($i - 1)->format('d/m/Y'),
                ];

                EmiPaid::create([
                    'lead_id' => $validated['lead_id'],
                    'sr_no' => $i,
                    'emi_month' => $emiDate->copy()->addMonths($i - 1)->format('M Y'),
                    'emi_amount' => $emi,
                    'penalty_amount' => 0,
                    'status' => 'Unpaid',
                    'expiry_date' => $emiDate->copy()->addMonths($i - 1),
                    'action_status' => 'Initiated',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $opening = $closing;
            }

            // Step 10: Update enach_approvals table
            Log::debug('Updating enach_approvals', ['lead_id' => $validated['lead_id']]);
            EnachApproval::updateOrCreate(
                ['lead_id' => $validated['lead_id']],
                [
                    'disbursement_date' => $validated['disbursement_date'],
                    'loan_type' => $validated['loan_type'],
                    'cash_mode' => $validated['cash_mode'],
                    'approved_loan_amount' => $principal,
                    'updated_at' => now(),
                ]
            );

            // Step 11: Store in session (optional)
            Log::debug('Storing EMI result in session', ['lead_id' => $validated['lead_id']]);
            session([
                'emi_result' => [
                    'lead_id' => $validated['lead_id'],
                    'principal' => $principal,
                    'disbursed_amount' => $principal,
                    'emi' => $emi,
                    'total_payment' => $totalPayment,
                    'total_interest' => $totalInterest,
                    'amortization' => $amortizationRecords,
                    'disbursement_date' => $validated['disbursement_date'],
                    'loan_type' => $validated['loan_type'],
                    'cash_mode' => $validated['cash_mode'],
                    'interest_rate' => $annualRate,
                    'tenure_months' => $months,
                ],
            ]);

            // Step 12: Log success
            Log::info('EMI Calculation Performed', [
                'lead_id' => $validated['lead_id'],
                'principal' => $principal,
                'emi' => $emi,
                'total_payment' => $totalPayment,
                'total_interest' => $totalInterest,
                'loan_type' => $validated['loan_type'],
                'cash_mode' => $validated['cash_mode'],
                'user_id' => Auth::id(),
            ]);

           // Step 13: Redirect to emi.show route with lead_id
return redirect()->route('emi.show', ['lead_id' => $validated['lead_id']]);


        } catch (ValidationException $e) {
            Log::error('Validation failed in showEMI', [
                'errors' => $e->errors(),
                'user_id' => Auth::id(),
                'lead_id' => $request->input('lead_id'),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Lead not found in showEMI', [
                'lead_id' => $request->input('lead_id'),
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lead not found or not approved.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('General error in showEMI', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lead_id' => $request->input('lead_id'),
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while calculating EMI.',
            ], 500);
        }
    }

public function showEMIResult(Request $request)
{
    try {
        $emiResult = session('emi_result');

        if (!$emiResult || !isset($emiResult['lead_id'])) {
            Log::warning('No EMI calculation data found in session', [
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('admin.adminApprovedEnachUserView')
                ->with('error', 'No EMI calculation data found.');
        }

        $lead_id = $emiResult['lead_id'];

        // Update disbursement_status to 'Approve' to trigger SQL population
        DB::table('enach_approvals')
            ->where('lead_id', $lead_id)
            ->update(['disbursement_status' => 'Approve']);

        // Wait briefly to let trigger execute (optional, based on your DB behavior)
        usleep(300000); // 300ms

        // Fetch from database for consistency
        $loan = LoanDetail::where('lead_id', $lead_id)->first();
        $emi_payments = EmiPaid::where('lead_id', $lead_id)
            ->orderBy('sr_no')
            ->get();

        if (!$loan) {
            Log::warning('No loan data found in database', [
                'lead_id' => $lead_id,
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('admin.adminApprovedEnachUserView')
                ->with('error', 'No loan data found.');
        }

        // Allow rendering even if emi_payments is empty
        if ($emi_payments->isEmpty()) {
            Log::info('No EMI payments found, proceeding with empty collection', [
                'lead_id' => $lead_id,
                'user_id' => Auth::id(),
            ]);
            $emi_payments = collect();
        }

        return view('emi.emi-result', compact('loan', 'emi_payments'));

    } catch (\Exception $e) {
        Log::error('Error in showEMIResult', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'lead_id' => $emiResult['lead_id'] ?? null,
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('admin.adminApprovedEnachUserView')
            ->with('error', 'An error occurred while retrieving EMI results.');
    }
}


    /**
     * Display EMI details from database.
     */
    public function show($lead_id)
    {
        try {
            Log::info('EmiController::show called', ['lead_id' => $lead_id, 'user_id' => Auth::id()]);
            $loan = LoanDetail::where('lead_id', $lead_id)->firstOrFail();
            $emi_payments = EmiPaid::where('lead_id', $lead_id)->orderBy('sr_no')->get();

            Log::info('Fetched loan and EMI payments', [
                'lead_id' => $lead_id,
                'loan' => $loan->toArray(),
                'emi_payments_count' => $emi_payments->count(),
                'user_id' => Auth::id(),
            ]);

            return view('emi.emi-result', compact('loan', 'emi_payments'));
        } catch (ModelNotFoundException $e) {
            Log::error('Loan not found in EmiController::show', [
                'lead_id' => $lead_id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Loan details not found.');
        } catch (\Exception $e) {
            Log::error('Error in EmiController::show', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lead_id' => $lead_id,
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Unable to load EMI details.');
        }
    }

    /**
     * Process EMI payment.
     */
    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'lead_id' => 'required|exists:loan_details,lead_id',
                'emi_number' => 'required|integer|min:1',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:credit_card,debit_card,net_banking,upi',
                'card_number' => 'required_if:payment_method,credit_card,debit_card|digits:16',
                'upi_id' => 'required_if:payment_method,upi|regex:/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/',
            ]);

            $emi = EmiPaid::where('lead_id', $request->lead_id)
                ->where('sr_no', $request->emi_number)
                ->firstOrFail();

            if ($emi->status === 'Paid') {
                return redirect()->back()->with('error', 'This EMI has already been paid.');
            }

            // Fetch loan to get interest rate
            $loan = LoanDetail::where('lead_id', $request->lead_id)->firstOrFail();
            $lead = Lead::where('lead_id', $request->lead_id)->firstOrFail();
            $annualRate = $lead->interest_rate ?? 18; // Use same rate as showEMI
            $monthlyRate = $annualRate / 1200;

            // Calculate interest and principal components
            $interest = round($loan->unpaid_principal * $monthlyRate, 2);
            $principal = round($request->amount - $interest, 2);

            // Update EMI record
            $emi->update([
                'status' => 'Paid',
                'txn_id' => 'TXN' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'action_status' => 'Completed',
            ]);

            // Update loan details
            $paidEmis = EmiPaid::where('lead_id', $request->lead_id)
                ->where('status', 'Paid')
                ->count();
            $totalEmis = EmiPaid::where('lead_id', $request->lead_id)->count();

            $loan->update([
                'amount_paid' => $loan->amount_paid + $request->amount,
                'amount_unpaid' => max(0, $loan->amount_unpaid - $request->amount),
                'unpaid_principal' => max(0, $loan->unpaid_principal - $principal),
                'unpaid_interest' => max(0, $loan->unpaid_interest - $interest),
                'emi_status' => "$paidEmis/$totalEmis Paid",
            ]);

            Log::info('EMI payment processed', [
                'lead_id' => $request->lead_id,
                'emi_number' => $request->emi_number,
                'amount' => $request->amount,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('emi.show', ['lead_id' => $request->lead_id])
                ->with('success', "Payment of ₹{$request->amount} for EMI {$request->emi_number} successful!");

        } catch (ValidationException $e) {
            Log::error('Validation failed in processPayment', [
                'errors' => $e->errors(),
                'lead_id' => $request->lead_id,
                'user_id' => Auth::id(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::error('EMI or loan not found in processPayment', [
                'lead_id' => $request->lead_id,
                'emi_number' => $request->emi_number,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return redirect()->back()->with('error', 'EMI or loan details not found.');
        } catch (\Exception $e) {
            Log::error('Error in processPayment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'lead_id' => $request->lead_id,
                'user_id' => Auth::id(),
            ]);
            return redirect()->back()->with('error', 'An error occurred while processing the payment.');
        }
    }
}

