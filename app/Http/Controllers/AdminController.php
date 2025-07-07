<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Loan;
use App\Models\EmiSchedule;
use App\Models\EnachApproval;
use App\Models\LoanPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;



class AdminController extends Controller
{
/**
     * Display a list of approved/disbursed KYC leads.
     */
    public function adminApprovedKycUserViewForm()
    {
        try {
            // Fetch leads with Approved or Disburse status, including search functionality
            $query = Lead::whereIn('kyc_status', ['Approved', 'Disburse']);

            // Apply search if provided
            if ($search = request()->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('lead_id', 'like', "%{$search}%")
                      ->orWhere('branch_name', 'like', "%{$search}%")
                      ->orWhere('employee_name', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('mobile_no', 'like', "%{$search}%");
                });
            }

            $leads = $query->paginate(10);

            Log::info('Approved or Disbursed KYC Leads Retrieved', [
                'count' => $leads->count(),
                'lead_ids' => $leads->pluck('lead_id')->toArray(),
                'user_id' => Auth::id(),
            ]);

            return view('admin.adminApprovedKycUserView', compact('leads'));
        } catch (\Exception $e) {
            Log::error('Error fetching approved/disbursed KYC leads', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            // Create an empty paginated collection to maintain view compatibility
            $emptyLeads = new LengthAwarePaginator([], 0, 10, 1, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);

            return view('admin.adminApprovedKycUserView', ['leads' => $emptyLeads])
                ->with('error', 'An error occurred while fetching approved/disbursed KYC users.');
        }
        
    }

    
/**
 * Display a list of rejected KYC leads.
 */
public function adminRejectedKycUserViewForm()
{
    try {
        $leads = Lead::where('kyc_status', 'Rejected')->paginate(10);

        Log::info('Rejected KYC Leads Retrieved', [
            'count' => $leads->count(),
            'lead_ids' => $leads->pluck('lead_id')->toArray(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.rejectedLead', compact('leads'));
    } catch (\Exception $e) {
        Log::error('Error fetching rejected KYC leads', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.rejectedLead', ['leads' => collect()])
            ->with('error', 'An error occurred while fetching rejected KYC users.');
    }
}

public function adminPendingKycUserViewForm()
{
    try {
        $leads = Lead::where('kyc_status', 'Pending')->paginate(10);

        Log::info('Pending KYC Leads Retrieved', [
            'count' => $leads->count(),
            'lead_ids' => $leads->pluck('lead_id')->toArray(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.pendingLead', compact('leads'));
    } catch (\Exception $e) {
        Log::error('Error fetching pending KYC leads', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.pendingLead', ['leads' => collect()])
            ->with('error', 'An error occurred while fetching pending KYC users.');
    }
}
 
public function closeLoan(Request $request)
{
    try {
        // Sanitize all editable amounts
        $unpaidPrincipal = floatval(preg_replace('/[^\d.]/', '', str_replace(',', '', $request->input('unpaid_principal'))));
        $nextEmiInterest = floatval(preg_replace('/[^\d.]/', '', str_replace(',', '', $request->input('next_month_interest'))));
        $foreclosureCharges = floatval(preg_replace('/[^\d.]/', '', str_replace(',', '', $request->input('foreclosure_charges'))));
        $remainingPenalty = floatval(preg_replace('/[^\d.]/', '', str_replace(',', '', $request->input('remaining_penalty'))));
        $closureAmount = floatval(preg_replace('/[^\d.]/', '', str_replace(',', '', $request->input('closure_amount'))));

        $request->merge([
            'closure_amount' => $closureAmount,
            'unpaid_principal' => $unpaidPrincipal,
            'next_month_interest' => $nextEmiInterest,
            'foreclosure_charges' => $foreclosureCharges,
            'remaining_penalty' => $remainingPenalty,
        ]);

        // Clear payment fields if not applicable
        if (!in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            $request->merge(['card_number' => null]);
        }
        if ($request->payment_method !== 'upi') {
            $request->merge(['upi_id' => null]);
        }

        // Validate input
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,lead_id',
            'loan_id' => 'required|exists:loan,loan_id',
            'payment_method' => 'required|in:credit_card,debit_card,net_banking,upi',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|nullable|digits:16',
            'upi_id' => 'required_if:payment_method,upi|nullable|regex:/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/',
            'closure_amount' => 'required|numeric|min:0',
            'unpaid_principal' => 'required|numeric|min:0',
            'next_month_interest' => 'required|numeric|min:0',
            'foreclosure_charges' => 'required|numeric|min:0',
            'remaining_penalty' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        $loan = Loan::lockForUpdate()->findOrFail($validated['loan_id']);

        if ($loan->lead_id != $validated['lead_id']) {
            return response()->json(['success' => false, 'message' => 'Invalid lead ID.'], 422);
        }

        if (strtolower($loan->current_status) === 'closed') {
            return response()->json(['success' => false, 'message' => 'Loan is already closed.'], 422);
        }

        $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();
        $emiSchedules = EmiSchedule::where('loan_id', $loan->loan_id)->get();
        $unpaidPayments = $loanPayments->where('status', '!=', 'Paid')->sortBy('emi_number');

        if ($unpaidPayments->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No unpaid EMIs to close.'], 422);
        }

        if ($emiSchedules->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No EMI schedules found for this loan.'], 422);
        }

        // Compute required closure amount from user-provided editable values
        $requiredClosureAmount = $unpaidPrincipal + $nextEmiInterest + $foreclosureCharges + $remainingPenalty;

        Log::info('Closure calculation breakdown (user input)', [
            'loan_id' => $loan->loan_id,
            'pending_principal' => $unpaidPrincipal,
            'next_emi_interest' => $nextEmiInterest,
            'foreclosure_charges' => $foreclosureCharges,
            'remaining_penalty' => $remainingPenalty,
            'required_closure_amount' => $requiredClosureAmount,
        ]);

        if ($closureAmount < $requiredClosureAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Closure amount must be at least ₹' . number_format($requiredClosureAmount, 2)
            ], 422);
        }

        // Pay all unpaid EMIs, set closure amount in first
        foreach ($unpaidPayments as $emi) {
            $emiSchedule = $emiSchedules->where('month', $emi->emi_number)->first();
            if (!$emiSchedule) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "EMI schedule for EMI #{$emi->emi_number} not found."
                ], 422);
            }

            $paymentAmount = $emiSchedule->emi_amount + ($emi->penalty_amount ?? 0);
            $closureAmountToSet = $emi->is($unpaidPayments->first()) ? $closureAmount : 0;

            $paymentRequest = new Request([
                'loan_id' => $loan->loan_id,
                'lead_id' => $loan->lead_id,
                'emi_number' => $emi->emi_number,
                'amount' => $paymentAmount,
                'payment_method' => $validated['payment_method'],
                'card_number' => $validated['card_number'] ?? null,
                'upi_id' => $validated['upi_id'] ?? null,
                'pay_type' => 'foreclose',
                'closure_amount' => $closureAmountToSet,
            ]);

            $paymentResponse = $this->makePayment($paymentRequest);

            if (!$paymentResponse->getData()->success) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "EMI #{$emi->emi_number} payment failed: " . ($paymentResponse->getData()->message ?? 'Unknown error')
                ], 422);
            }

            LoanPayment::where('loan_id', $loan->loan_id)
                ->where('emi_number', $emi->emi_number)
                ->update(['closure_amount' => $closureAmountToSet]);
        }

        // Update loan summary
        $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();
        $emiSchedules = EmiSchedule::where('loan_id', $loan->loan_id)->get();

        $amountPaid = $loanPayments->where('status', 'Paid')->sum('amount');
        $totalPayment = $emiSchedules->sum('emi_amount');
        $totalInterest = $emiSchedules->sum('interest');
        $paidPrincipal = $loanPayments->where('status', 'Paid')->sum('principal_paid');
        $totalPenalty = $loanPayments->sum('penalty_amount');
        $paidPenalty = $loanPayments->where('status', 'Paid')->sum('penalty_amount');
        $emiPaidCount = $loanPayments->where('status', 'Paid')->count();
        $emiStatus = "{$emiPaidCount}/{$loan->tenure_months} Paid";

        $loan->update([
            'current_status' => 'closed',
            'amount_paid' => $amountPaid,
            'amount_unpaid' => 0,
            'unpaid_principal' => 0,
            'unpaid_interest' => 0,
            'total_penalty' => $totalPenalty,
            'paid_penalty' => $paidPenalty,
            'remaining_penalty' => 0,
            'total_amount' => $totalPayment,
            'total_interest' => $totalInterest,
            'closure_amount' => 0,
            'emi_status' => $emiStatus,
            'closed_on' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Loan closed successfully.',
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        Log::error('Loan closure failed: Model not found', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Loan or related records not found.'], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        Log::error('Loan closure failed: Validation error', ['error' => $e->getMessage(), 'errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Loan closure failed', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Loan closure failed: ' . $e->getMessage()], 500);
    }
}


 public function makePayment(Request $request)
{
    try {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loan,loan_id',
            'lead_id' => 'required|exists:leads,lead_id',
            'emi_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:credit_card,debit_card,net_banking,upi,cash',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|nullable|digits:16',
            'upi_id' => 'required_if:payment_method,upi|nullable|regex:/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/',
            'pay_type' => 'nullable|in:normal,foreclose',
        ]);

        DB::beginTransaction();

        $loan = Loan::lockForUpdate()->findOrFail($validated['loan_id']);
        if ($loan->lead_id != $validated['lead_id']) {
            return response()->json(['success' => false, 'message' => 'Invalid lead ID.'], 422);
        }

        $emiSchedule = EmiSchedule::where('loan_id', $validated['loan_id'])
            ->where('month', $validated['emi_number'])
            ->first();

        if (!$emiSchedule) {
            return response()->json(['success' => false, 'message' => 'EMI schedule not found.'], 404);
        }

        $loanPayment = LoanPayment::where('loan_id', $validated['loan_id'])
            ->where('emi_number', $validated['emi_number'])
            ->lockForUpdate()
            ->first();

        if (!$loanPayment) {
            return response()->json(['success' => false, 'message' => 'Loan payment record not found.'], 404);
        }

        if ($loanPayment->status === 'Paid') {
            return response()->json(['success' => false, 'message' => 'EMI already marked as paid.'], 422);
        }

        $incomingPayType = $validated['pay_type'] ?? 'normal';
        $currentPayType = $loanPayment->pay_type ?? 'normal';

        // ❗ Allow if current pay_type is normal, only block repeated foreclose
        if (
            $incomingPayType === $currentPayType &&
            $loanPayment->status !== 'Paid' &&
            $loanPayment->amount > 0 &&
            $incomingPayType !== 'normal'
        ) {
            Log::warning('Blocked payment due to unchanged non-normal pay_type', [
                'loan_id' => $validated['loan_id'],
                'emi_number' => $validated['emi_number'],
                'current_pay_type' => $currentPayType,
                'incoming_pay_type' => $incomingPayType,
                'status' => $loanPayment->status,
                'amount' => $loanPayment->amount,
                'half_payment_paid' => $loanPayment->half_payment_paid,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment not allowed unless pay_type is changed from previous non-normal type.',
            ], 422);
        }

        $expectedAmount = round($emiSchedule->emi_amount + ($loanPayment->penalty_amount ?? 0), 2);
        $paidAmount = $validated['amount'];
        $reference = $validated['card_number'] ?? $validated['upi_id'] ?? 'N/A';

        $newPartialPaid = $loanPayment->half_payment_paid + $paidAmount;

        if ($newPartialPaid >= $expectedAmount) {
            $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();
            $paidPrincipalSoFar = $loanPayments->where('status', 'Paid')->sum('principal_paid');
            $remainingPrincipal = max(0, $loan->approved_loan_amount - $paidPrincipalSoFar);
            $principalToPay = min($emiSchedule->principal, $remainingPrincipal);

            $loanPayment->update([
                'amount' => $expectedAmount,
                'principal_paid' => $principalToPay,
                'interest_paid' => $emiSchedule->interest ?? 0,
                'status' => 'Paid',
                'pay_type' => $incomingPayType,
                'half_payment_paid' => 0,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $reference,
                'paid_at' => now(),
                'emi_payment_date' => now(),
            ]);

            $emiSchedule->update(['status' => 'paid']);
        } else {
            $loanPayment->update([
                'status' => 'Unpaid',
                'pay_type' => $incomingPayType,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $reference,
                'paid_at' => now(),
                'emi_payment_date' => now(),
            ]);

            $loanPayment->increment('half_payment_paid', $paidAmount);
        }

        // === Update Loan Summary ===
        $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();
        $emiSchedules = EmiSchedule::where('loan_id', $loan->loan_id)->get();

        $amountPaid = $loanPayments->where('status', 'Paid')->sum('amount');
        $totalPayment = $emiSchedules->sum('emi_amount');
        $totalInterest = $emiSchedules->sum('interest');
        $paidPrincipal = $loanPayments->where('status', 'Paid')->sum('principal_paid');
        $unpaidPrincipal = max(0, $loan->approved_loan_amount - $paidPrincipal);
        $unpaidInterest = max(0, $totalInterest - $loanPayments->where('status', 'Paid')->sum('interest_paid'));
        $amountUnpaid = max(0, $totalPayment - $amountPaid);
        $totalPenalty = $loanPayments->sum('penalty_amount');
        $paidPenalty = $loanPayments->where('status', 'Paid')->sum('penalty_amount');
        $remainingPenalty = $totalPenalty - $paidPenalty;
        $emiPaidCount = $loanPayments->where('status', 'Paid')->count();
        $emiStatus = "$emiPaidCount/{$loan->tenure_months} Paid";

        $updateData = [
            'amount_paid' => $amountPaid,
            'amount_unpaid' => $amountUnpaid,
            'unpaid_principal' => $unpaidPrincipal,
            'unpaid_interest' => $unpaidInterest,
            'total_penalty' => $totalPenalty,
            'paid_penalty' => $paidPenalty,
            'remaining_penalty' => $remainingPenalty,
            'total_amount' => $totalPayment,
            'total_interest' => $totalInterest,
            'emi_status' => $emiStatus,
        ];

        if ($emiPaidCount === 1 && $loan->disbursement_status !== 'Disbursed') {
            $updateData['disbursement_status'] = 'Disbursed';
            $updateData['payment_mode'] = $validated['payment_method'];
        }

        if ($emiPaidCount === $loan->tenure_months && $amountUnpaid <= 0 && $unpaidPrincipal <= 0) {
            $updateData['current_status'] = 'closed';
            $updateData['closed_on'] = now();
        }

        $loan->update($updateData);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $newPartialPaid >= $expectedAmount ? 'Full EMI paid.' : 'Partial payment recorded. EMI remains unpaid.',
            'status' => $newPartialPaid >= $expectedAmount ? 'Paid' : 'Unpaid',
            'paid_amount' => $newPartialPaid,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Payment Error', ['message' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
    }
}

public function adminApprovedEnachUserView()
{
    try {
        $enachApprovals = EnachApproval::where('status_of_enach', 'approve')
            ->orderBy('created_at', 'desc') // Show latest first
            ->paginate(10);

        Log::info('Approved ENACH Approvals Retrieved', [
            'count' => $enachApprovals->count(),
            'lead_ids' => $enachApprovals->pluck('lead_id')->toArray(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.adminApprovedEnachUserView', compact('enachApprovals'));
    } catch (\Exception $e) {
        Log::error('Error fetching approved ENACH approvals', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.adminApprovedEnachUserView', ['enachApprovals' => collect()])
            ->with('error', 'An error occurred while fetching approved ENACH records.');
    }
}


public function adminApprovedKycUserEmiCalculationViewForm($lead_id)
{
    try {
        // Ensure user is authenticated
        if (!Auth::check()) {
            Log::error('Unauthenticated access attempt to EMI calculation form', ['lead_id' => $lead_id]);
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Validate lead_id
        Log::info('Received lead_id', ['lead_id' => $lead_id]);
        if (!is_numeric($lead_id) || $lead_id <= 0) {
            throw new \InvalidArgumentException('Invalid lead ID provided.');
        }

        // Fetch loan by lead_id
        $loan = Loan::where('lead_id', $lead_id)->firstOrFail();

        Log::info('EMI Calculation Form Loaded for Loan', [
            'lead_id' => $lead_id,
            'loan_id' => $loan->loan_id,
            'loan' => $loan->toArray(),
            'user_id' => Auth::id(),
        ]);

        return view('admin.adminApprovedKycUserEmiCalculation', compact('loan'));
    } catch (ModelNotFoundException $e) {
        Log::error('Loan not found for EMI calculation', [
            'lead_id' => $lead_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('admin.adminApprovedKycUserView')->with('error', 'The requested loan was not found.');
    } catch (\Exception $e) {
        Log::error('Error loading EMI calculation form', [
            'lead_id' => $lead_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('admin.adminApprovedKycUserView')->with('error', 'An error occurred while loading the EMI calculation form.');
    }
}


public function approveEmiForEnach(Request $request, $lead_id)
{
    Log::info('approveEmiForEnach called', [
        'lead_id' => $lead_id,
        'request_data' => $request->all(),
        'user_id' => Auth::id(),
    ]);

    try {
        \DB::beginTransaction();

        $loan = Loan::where('lead_id', $lead_id)
            ->where('bank_verification_status', 'Verified')
            ->orderByDesc('loan_id')
            ->lockForUpdate()
            ->firstOrFail();

        Log::info('Overwriting existing ENACH status if any', [
            'loan_id' => $loan->loan_id,
            'previous_status' => $loan->status_of_enach,
            'user_id' => Auth::id(),
        ]);

        $loan_id = $loan->loan_id;
        $request->merge(['loan_id' => $loan_id]);

        $rules = [
            'calculation_method' => 'required|in:flat,reduce',
            'approved_loan_amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0.01',
            'tenure_months' => 'required|integer|min:1',
            'processing_fees' => 'required|numeric|min:0',
            'gst_on_processing_fees' => 'required|numeric|min:0',
            'gst_amount' => 'required|numeric|min:0',
            'insurance_charges' => 'required|numeric|min:0',
            'credit_report_charges' => 'required|numeric|min:0',
            'disbursement_amount' => 'required|numeric|min:0',
            'emi' => 'required|numeric|min:0',
            'total_payment' => 'required|numeric|min:0',
            'total_interest' => 'required|numeric|min:0',
            'action' => 'required|in:approve,reject',
        ];

        if ($request->input('action') === 'reject') {
            $rules['rejection_reason'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        $loanAmount = round($validated['approved_loan_amount']);
        $monthlyRate = $validated['interest_rate'] / 100 / 12;
        $tenure = (int) $validated['tenure_months'];
        $method = $validated['calculation_method'];

        if ($method === 'reduce') {
            $x = pow(1 + $monthlyRate, $tenure);
            if ($x <= 1) {
                \DB::rollBack();
                return back()->withErrors(['error' => 'Invalid calculation parameters for reducing balance method.'])->withInput();
            }
            $emi = ($loanAmount * $monthlyRate * $x) / ($x - 1);
            $totalPayment = $emi * $tenure;
            $totalInterest = $totalPayment - $loanAmount;
        } else {
            $totalInterest = $loanAmount * ($validated['interest_rate'] / 100) * ($tenure / 12);
            $totalPayment = $loanAmount + $totalInterest;
            $emi = $totalPayment / $tenure;
        }

        // ✅ Round to nearest integer
        $emi = round($emi);
        $totalPayment = round($totalPayment);
        $totalInterest = round($totalInterest);
        $gstAmount = round($validated['processing_fees'] * ($validated['gst_on_processing_fees'] / 100));
        $disbursementAmount = round(
            $loanAmount
            - round($validated['processing_fees'])
            - $gstAmount
            - round($validated['insurance_charges'])
            - round($validated['credit_report_charges'])
        );

        if ($disbursementAmount < 0) {
            \DB::rollBack();
            return back()->withErrors(['disbursement_amount' => 'Disbursement amount cannot be negative.'])->withInput();
        }

        $tolerance = 1;
        $mismatches = [];

        if (abs($emi - round($validated['emi'])) > $tolerance) {
            $mismatches[] = "EMI mismatch (Expected: $emi, Provided: {$validated['emi']})";
        }
        if (abs($totalPayment - round($validated['total_payment'])) > $tolerance) {
            $mismatches[] = "Total Payment mismatch (Expected: $totalPayment, Provided: {$validated['total_payment']})";
        }
        if (abs($totalInterest - round($validated['total_interest'])) > $tolerance) {
            $mismatches[] = "Interest mismatch (Expected: $totalInterest, Provided: {$validated['total_interest']})";
        }
        if (abs($gstAmount - round($validated['gst_amount'])) > $tolerance) {
            $mismatches[] = "GST mismatch (Expected: $gstAmount, Provided: {$validated['gst_amount']})";
        }
        if (abs($disbursementAmount - round($validated['disbursement_amount'])) > $tolerance) {
            $mismatches[] = "Disbursement mismatch (Expected: $disbursementAmount, Provided: {$validated['disbursement_amount']})";
        }

        if (!empty($mismatches)) {
            Log::warning('Calculation mismatches', [
                'loan_id' => $loan_id,
                'mismatches' => $mismatches,
                'user_id' => Auth::id(),
            ]);
            \DB::rollBack();
            return back()->withErrors(['error' => 'Calculation mismatches detected: ' . implode(', ', $mismatches)])->withInput();
        }

        if (!Auth::check()) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'User authentication required. Please log in again.']);
        }

        $loan->update([
            'approved_loan_amount' => $loanAmount,
            'interest_rate' => round($validated['interest_rate'], 2),
            'tenure_months' => $tenure,
            'processing_fees' => round($validated['processing_fees']),
            'gst_on_processing_fees' => round($validated['gst_on_processing_fees']),
            'gst_amount' => $gstAmount,
            'insurance_charges' => round($validated['insurance_charges']),
            'credit_report_charges' => round($validated['credit_report_charges']),
            'disbursement_amount' => $disbursementAmount,
            'emi' => $emi,
            'total_loan' => $totalPayment,
            'total_payment' => $totalPayment,
            'total_interest' => $totalInterest,
            'calculation_method' => $method,
            'status_of_enach' => $validated['action'],
            'rejection_reason' => $validated['action'] === 'reject' ? $validated['rejection_reason'] : null,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        \DB::commit();

        Log::info('EMI approval processed', [
            'loan_id' => $loan_id,
            'lead_id' => $lead_id,
            'action' => $validated['action'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.adminApprovedEnachUserView')
            ->with('success', 'ENACH EMI ' . ($validated['action'] === 'approve' ? 'approved' : 'rejected') . ' successfully.');
    } catch (ValidationException $e) {
        \DB::rollBack();
        return back()->withErrors($e->errors())->withInput();
    } catch (ModelNotFoundException $e) {
        \DB::rollBack();
        Log::error('Loan not found', ['lead_id' => $lead_id, 'user_id' => Auth::id()]);
        return back()->withErrors(['loan_id' => 'Loan not found or bank verification not completed.']);
    } catch (\Exception $e) {
        \DB::rollBack();
        Log::error('Unexpected error in approveEmiForEnach', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'lead_id' => $lead_id,
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);
        return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    }
}




public function showEMI(Request $request)
{
    try {
        Log::debug('showEMI input', [
            'input' => $request->all(),
            'user_id' => Auth::id(),
        ]);

        // Validate request inputs
        $validated = $request->validate([
            'lead_id' => ['required', 'exists:loan,lead_id'],
            'disbursement_date' => ['required', 'date', 'before_or_equal:today'],
            'emi_date' => ['required', 'date'],
            'loan_type' => ['required', 'string'],
            'payment_mode' => ['required', 'string'],
        ]);

        if (empty($validated['emi_date'])) {
            Log::warning('EMI date is missing, EMI will not be calculated.', [
                'lead_id' => $validated['lead_id'],
                'user_id' => Auth::id(),
            ]);

            DB::rollBack();
            return redirect()->back()->with('error', 'EMI date is required to calculate EMI.');
        }

        DB::beginTransaction();

        // Fetch the latest approved loan for the given lead_id
        $loan = Loan::where('lead_id', $validated['lead_id'])
            ->where('status_of_enach', 'approve')
            ->latest('updated_at')
            ->first();

        if (!$loan || !$loan->loan_id) {
            Log::error('Invalid loan or missing ID', ['lead_id' => $validated['lead_id']]);
            return redirect()->back()->with('error', 'No approved loan found or loan ID missing.');
        }

        // Loan Parameters
        $principal = floatval($loan->approved_loan_amount ?? $loan->loan_amount ?? 0);
        $annualRate = floatval($loan->interest_rate ?? 12);
        $months = intval($loan->tenure_months ?? 12);
        $method = strtolower($loan->calculation_method ?? 'flat');
        $monthlyRate = $annualRate / 1200;

        if ($principal <= 0 || $months <= 0 || $annualRate < 0) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Invalid loan data: Principal, tenure, or interest rate is invalid.');
        }

        // Calculate EMI
        if ($method === 'reduce') {
            $x = pow(1 + $monthlyRate, $months);
            $emi = ($x - 1 == 0) ? ($principal / $months) : (($principal * $monthlyRate * $x) / ($x - 1));
            $totalPayment = $emi * $months;
            $totalInterest = $totalPayment - $principal;
        } else {
            $totalInterest = $principal * ($annualRate / 100) * ($months / 12);
            $totalPayment = $principal + $totalInterest;
            $emi = $totalPayment / $months;
        }

        $emi = round($emi, 0);
        $totalPayment = round($totalPayment, 0);
        $totalInterest = round($totalInterest, 0);

        // Amortization Schedule
        $amortization = [];
        $opening = $principal;
        $startEmiDate = Carbon::parse($validated['emi_date']);

        for ($i = 1; $i <= $months; $i++) {
            $dueDate = $startEmiDate->copy()->addMonths($i - 1);
            $interest = $method === 'reduce'
                ? round($opening * $monthlyRate, 0)
                : round($totalInterest / $months, 0);
            $principalPart = round($emi - $interest, 0);
            $closing = round(max($opening - $principalPart, 0), 0);

            $amortization[] = [
                'month' => $i,
                'opening_balance' => $opening,
                'emi_amount' => $emi,
                'principal' => $principalPart,
                'interest' => $interest,
                'closing_balance' => $closing,
                'due_date' => $dueDate->format('Y-m-d'),
            ];

            $opening = $closing;
        }

        // Update loan
        $loan->update([
            'loan_status' => 'disbursed',
            'emi' => $emi,
            'total_interest' => $totalInterest,
            'total_loan' => $totalPayment,
            'disbursement_status' => 'Disbursed',
            'disbursement_date' => $validated['disbursement_date'],
            'loan_type' => $validated['loan_type'],
            'payment_mode' => $validated['payment_mode'],
            'updated_at' => now(),
        ]);

        // Clear previous schedules
        EmiSchedule::where('loan_id', $loan->loan_id)->delete();
        LoanPayment::where('loan_id', $loan->loan_id)->delete();

        // Insert EMI Schedules
        foreach ($amortization as $entry) {
            EmiSchedule::create([
                'loan_id' => $loan->loan_id,
                'lead_id' => $validated['lead_id'],
                'month' => $entry['month'],
                'emi_amount' => $entry['emi_amount'],
                'principal' => $entry['principal'],
                'interest' => $entry['interest'],
                'opening_balance' => $entry['opening_balance'],
                'closing_balance' => $entry['closing_balance'],
                'due_date' => $entry['due_date'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert EMI Payments
        foreach ($amortization as $entry) {
            $dueDate = Carbon::parse($entry['due_date']);
            $isPaid = $dueDate->lt(Carbon::today());

            LoanPayment::create([
                'loan_id' => $loan->loan_id,
                'emi_number' => $entry['month'],
                'amount' => $entry['emi_amount'],
                'principal_paid' => $isPaid ? $entry['principal'] : 0,
                'interest_paid' => $isPaid ? $entry['interest'] : 0,
                'emi_date' => $entry['due_date'],
                'emi_payment_date' => $isPaid ? Carbon::now()->format('Y-m-d') : null,
                'status' => $isPaid ? 'Paid' : 'Unpaid',
                'penalty_amount' => 0,
                'is_first_time_overdue' => false,
                'fixed_penalty_applied' => false,
                'last_penalty_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Save session
        session([
            'emi_result' => [
                'loan_id' => $loan->loan_id,
                'lead_id' => $validated['lead_id'],
                'principal' => $principal,
                'emi' => $emi,
                'total_payment' => $totalPayment,
                'total_interest' => $totalInterest,
                'amortization' => $amortization,
                'disbursement_date' => $validated['disbursement_date'],
                'loan_type' => $validated['loan_type'],
                'payment_mode' => $validated['payment_mode'],
                'interest_rate' => $annualRate,
                'tenure_months' => $months,
                'calculation_method' => $method,
            ],
        ]);

        DB::commit();

        return redirect()->route('emi.result', ['lead_id' => $validated['lead_id']])
            ->with('success', 'Loan disbursed and EMI schedule generated successfully.');

    } catch (ValidationException $e) {
        DB::rollBack();
        Log::error('Validation error in showEMI', ['errors' => $e->errors()]);
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in showEMI', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Error generating EMI schedule: ' . $e->getMessage());
    }
}

   /**
 * Display EMI result for a given lead_id.
 *
 * @param Request $request
 * @param string $lead_id
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function showEMIResult(Request $request, $lead_id)
{
    Log::info('showEMIResult accessed', [
        'user_id' => Auth::id(),
        'session_id' => session()->getId(),
        'lead_id' => $lead_id,
    ]);

    try {
        $emiResult = session('emi_result');

        if (!$emiResult || !isset($emiResult['lead_id']) || $emiResult['lead_id'] !== $lead_id) {
            $loan = Loan::where('lead_id', $lead_id)
                ->where('status_of_enach', 'approve')
                ->latest('loan_id')
                ->first();

            if (!$loan) {
                Log::error('Loan not found or not approved', ['lead_id' => $lead_id]);
                return redirect()->route('admin.adminApprovedEnachUserView')
                    ->with('error', 'Loan not found or not approved.');
            }

            $principal = floatval($loan->approved_loan_amount ?? $loan->loan_amount ?? 0);
            $annualRate = floatval($loan->interest_rate ?? 12);
            $months = intval($loan->tenure_months ?? 12);
            $method = strtolower($loan->calculation_method ?? 'flat');
            $monthlyRate = $annualRate / 1200;

            if ($principal <= 0 || $months <= 0 || $annualRate < 0) {
                Log::error('Invalid loan data', ['lead_id' => $lead_id, 'principal' => $principal, 'months' => $months, 'annualRate' => $annualRate]);
                return redirect()->route('admin.adminApprovedEnachUserView')
                    ->with('error', 'Invalid loan data. Please verify loan details.');
            }

            if ($method === 'reduce') {
                $x = pow(1 + $monthlyRate, $months);
                $emi = ($x - 1 == 0) ? ($principal / $months) : (($principal * $monthlyRate * $x) / ($x - 1));
                $totalPayment = $emi * $months;
                $totalInterest = $totalPayment - $principal;
            } else {
                $totalInterest = $principal * ($annualRate / 100) * ($months / 12);
                $totalPayment = $principal + $totalInterest;
                $emi = $totalPayment / $months;
            }

            $emi = round($emi, 0);
            $totalPayment = round($totalPayment, 0);
            $totalInterest = round($totalInterest, 0);

            $amortization = [];
            $opening = $principal;
            $emiDate = Carbon::parse($loan->disbursement_date)->endOfMonth();

            for ($i = 1; $i <= $months; $i++) {
                $interest = $method === 'reduce' ? round($opening * $monthlyRate, 0) : round($totalInterest / $months, 0);
                $principalPart = round($emi - $interest, 0);
                $closing = round(max($opening - $principalPart, 0), 0);

                $amortization[] = [
                    'month' => $i,
                    'opening_balance' => $opening,
                    'emi_amount' => $emi,
                    'principal' => $principalPart,
                    'interest' => $interest,
                    'closing_balance' => $closing,
                    'due_date' => $emiDate->copy()->addMonths($i - 1)->format('Y-m-d'),
                ];
                $opening = $closing;
            }

            $emiResult = [
                'loan_id' => $loan->loan_id,
                'lead_id' => $loan->lead_id,
                'principal' => $principal,
                'emi' => $emi,
                'total_payment' => $totalPayment,
                'total_interest' => $totalInterest,
                'amortization' => $amortization,
                'disbursement_date' => $loan->disbursement_date,
                'loan_type' => $loan->loan_type,
                'payment_mode' => $loan->payment_mode,
                'interest_rate' => $annualRate,
                'tenure_months' => $months,
                'calculation_method' => $method,
            ];

            session(['emi_result' => $emiResult]);
        }

        $loan = Loan::with(['loanPayments', 'emiSchedules'])->findOrFail($emiResult['loan_id']);
        $emiSchedules = EmiSchedule::where('loan_id', $loan->loan_id)->get();
        $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();

        if ($emiSchedules->isEmpty()) {
            Log::warning('No EMI schedules found, regenerating', ['loan_id' => $loan->loan_id]);
            EmiSchedule::where('loan_id', $loan->loan_id)->delete();
            LoanPayment::where('loan_id', $loan->loan_id)->delete();

            foreach ($emiResult['amortization'] as $entry) {
                EmiSchedule::create([
                    'loan_id' => $loan->loan_id,
                    'lead_id' => $loan->lead_id,
                    'month' => $entry['month'],
                    'emi_amount' => $entry['emi_amount'],
                    'principal' => $entry['principal'],
                    'interest' => $entry['interest'],
                    'opening_balance' => $entry['opening_balance'],
                    'closing_balance' => $entry['closing_balance'],
                    'due_date' => $entry['due_date'],
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $dueDate = Carbon::parse($entry['due_date']);
                $isPaid = $dueDate->lt(Carbon::today());

                LoanPayment::create([
                    'loan_id' => $loan->loan_id,
                    'emi_number' => $entry['month'],
                    'amount' => $entry['emi_amount'],
                    'principal_paid' => $isPaid ? $entry['principal'] : 0,
                    'interest_paid' => $isPaid ? $entry['interest'] : 0,
                    'emi_date' => $entry['due_date'],
                    'emi_payment_date' => $isPaid ? Carbon::now()->format('Y-m-d') : null,
                    'status' => $isPaid ? 'Paid' : 'Unpaid',
                    'penalty_amount' => 0,
                    'is_first_time_overdue' => false,
                    'fixed_penalty_applied' => false,
                    'last_penalty_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $emiSchedules = EmiSchedule::where('loan_id', $loan->loan_id)->get();
            $loanPayments = LoanPayment::where('loan_id', $loan->loan_id)->get();
        }

        // ✅ Closure Amount Calculation
        $totalInterest = $emiSchedules->sum('interest');
        $tenureMonths = $loan->tenure_months ?? 12;
        $paidPrincipal = $loanPayments->where('status', 'Paid')->sum('principal_paid');
        $pendingPrincipal = max(0, $loan->approved_loan_amount - $paidPrincipal);
        $foreclosureCharges = round($pendingPrincipal * 0.04, 0);
        $nextEmiInterest = round($totalInterest / $tenureMonths, 0);
        $remainingPenalty = $loanPayments->where('status', '!=', 'Paid')->sum('penalty_amount');

        $closureAmount = $pendingPrincipal + $nextEmiInterest + $foreclosureCharges + $remainingPenalty;

        return view('emi.emi-result', compact('loan', 'emiSchedules', 'loanPayments', 'closureAmount'));

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Loan not found in showEMIResult', ['lead_id' => $lead_id, 'error' => $e->getMessage()]);
        return redirect()->route('admin.adminApprovedEnachUserView')
            ->with('error', 'Loan not found. Please verify and try again.');
    } catch (\Exception $e) {
        Log::error('Error in showEMIResult', ['lead_id' => $lead_id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->route('admin.adminApprovedEnachUserView')
            ->with('error', 'Something went wrong while loading EMI result: ' . $e->getMessage());
    }
}





}