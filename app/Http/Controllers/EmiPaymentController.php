<?php
namespace App\Http\Controllers;

use App\Models\LoanDetail;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmiPaymentController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'lead_id' => 'required|exists:loan_details,lead_id',
            'emi_number' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:credit_card,debit_card,net_banking,upi',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|nullable|string|regex:/^\d{16}$/',
            'upi_id' => 'required_if:payment_method,upi|nullable|string|regex:/^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/'
        ]);

        // Find loan
        $loan = LoanDetail::where('lead_id', $request->lead_id)->first();
        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }

        // Check if EMI already paid
        $existingPayment = LoanPayment::where('loan_id', $loan->id)
            ->where('emi_number', $request->emi_number)
            ->where('status', 'Paid')
            ->first();
        if ($existingPayment) {
            return response()->json(['success' => false, 'message' => 'EMI already paid'], 400);
        }

        // Calculate principal and interest
        $monthlyRate = 18.0 / 1200; // 18% annual rate
        $openingBalance = $loan->unpaid_principal;
        $interest = round($openingBalance * $monthlyRate, 2);
        $principal = round($request->amount - $interest, 2);

        // Save payment
        $payment = LoanPayment::create([
            'loan_id' => $loan->id,
            'emi_number' => $request->emi_number,
            'amount' => $request->amount,
            'principal_paid' => $principal,
            'interest_paid' => $interest,
            'payment_method' => $request->payment_method,
            'txn_id' => 'TXN' . strtoupper(uniqid()),
            'emi_date' => Carbon::parse($loan->disbursement_date)->addMonths($request->emi_number - 1)->endOfMonth(),
            'payment_date' => now(),
            'penalty_amount' => 0,
            'status' => 'Paid'
        ]);

        // Update loan_details table
        $totalPaid = $loan->loanPayments()->where('status', 'Paid')->sum('amount');
        $unpaidPrincipal = $loan->approved_loan_amount - $loan->loanPayments()->where('status', 'Paid')->sum('principal_paid');
        $unpaidInterest = $loan->interest - $loan->loanPayments()->where('status', 'Paid')->sum('interest_paid');
        $totalPenalty = $loan->loanPayments()->sum('penalty_amount');
        $paidPenalty = $loan->loanPayments()->where('status', 'Paid')->sum('penalty_amount');
        $paidEmis = $loan->loanPayments()->where('status', 'Paid')->count();
        $totalEmis = (int) explode('/', $loan->emi_status)[1]; // Extract total EMIs from emi_status (e.g., "0/12")

        $loan->update([
            'amount_paid' => $totalPaid,
            'amount_unpaid' => $loan->total_amount - $totalPaid,
            'unpaid_principal' => $unpaidPrincipal,
            'unpaid_interest' => $unpaidInterest,
            'total_penalty' => $totalPenalty,
            'paid_penalty' => $paidPenalty,
            'remaining_penalty' => $totalPenalty - $paidPenalty,
            'emi_status' => "{$paidEmis}/{$totalEmis}",
            'updated_at' => now()
        ]);

        // Log for debugging
        Log::info('EMI Payment Saved', [
            'loan_id' => $loan->id,
            'emi_number' => $request->emi_number,
            'amount' => $request->amount,
            'txn_id' => $payment->txn_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'txnId' => $payment->txn_id
        ]);
    }

    public function details($loanId)
    {
        $loan = LoanDetail::with('loanPayments')->findOrFail($loanId);
        $totalPaid = $loan->loanPayments->where('status', 'Paid')->sum('amount');
        $unpaidPrincipal = $loan->approved_loan_amount - $loan->loanPayments->where('status', 'Paid')->sum('principal_paid');
        $unpaidInterest = $loan->interest - $loan->loanPayments->where('status', 'Paid')->sum('interest_paid');
        $totalPenalty = $loan->loanPayments->sum('penalty_amount');
        $paidPenalty = $loan->loanPayments->where('status', 'Paid')->sum('penalty_amount');
        $emiPaidCount = $loan->loanPayments->where('status', 'Paid')->count();
        $totalEmis = (int) explode('/', $loan->emi_status)[1];

        return response()->json([
            'amountPaid' => round($totalPaid, 0),
            'amountUnpaid' => round($loan->total_amount - $totalPaid, 0),
            'unpaidPrincipal' => round($unpaidPrincipal, 0),
            'unpaidInterest' => round($unpaidInterest, 0),
            'totalPenalty' => round($totalPenalty, 0),
            'paidPenalty' => round($paidPenalty, 0),
            'remainingPenalty' => round($totalPenalty - $paidPenalty, 0),
            'emiStatus' => "{$emiPaidCount}/{$totalEmis}"
        ]);
    }
}