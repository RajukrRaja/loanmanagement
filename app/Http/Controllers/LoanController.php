<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function disburse(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|unique:loans,lead_id',
            'loan_type' => 'required|in:Housing Loan,Property Loan,Personal Loan,Consumer Loan,Gold Loan,Education Loan,Two Wheeler Loan,Tractor Loan,Business Loan - Secured,Business Loan - General',
            'payment_mode' => 'required|in:Cash,NEFT,RTGS,Cheque,UPI',
            'disbursement_date' => 'required|date',
            'loan_amount' => 'required|numeric|min:0',
            'disbursed_amount' => 'required|numeric|min:0',
            'tenure_months' => 'required|integer|min:1',
            'interest_amount' => 'required|numeric|min:0'
        ]);

        $monthlyRate = 18.0 / 1200; // 18% annual rate
        $tenureMonths = $request->tenure_months;
        $loanAmount = $request->loan_amount;
        $emiAmount = round(($loanAmount * $monthlyRate * pow(1 + $monthlyRate, $tenureMonths)) / (pow(1 + $monthlyRate, $tenureMonths) - 1), 2);

        // Create loan
        $loan = Loan::create([
            'lead_id' => $request->lead_id,
            'loan_type' => $request->loan_type,
            'payment_mode' => $request->payment_mode,
            'disbursement_date' => $request->disbursement_date,
            'emi_date' => Carbon::parse($request->disbursement_date)->endOfMonth(),
            'loan_amount' => $loanAmount,
            'disbursed_amount' => $request->disbursed_amount,
            'amount_paid' => 0,
            'amount_unpaid' => $loanAmount + $request->interest_amount,
            'unpaid_principal' => $loanAmount,
            'unpaid_interest' => $request->interest_amount,
            'total_penalty' => 0,
            'paid_penalty' => 0,
            'remain_penalty' => 0,
            'total_amount' => $loanAmount + $request->interest_amount,
            'interest_amount' => $request->interest_amount,
            'status_paid_count' => 0,
            'status_total_count' => $tenureMonths,
            'status' => 'active'
        ]);

        // Create EMI records
        $openingBalance = $loanAmount;
        for ($i = 1; $i <= $tenureMonths; $i++) {
            $interest = round($openingBalance * $monthlyRate, 2);
            $principal = round($emiAmount - $interest, 2);
            LoanPayment::create([
                'loan_id' => $loan->id,
                'emi_number' => $i,
                'amount' => $emiAmount,
                'principal_paid' => 0,
                'interest_paid' => 0,
                'emi_date' => Carbon::parse($loan->disbursement_date)->addMonths($i - 1)->endOfMonth(),
                'status' => 'Unpaid'
            ]);
            $openingBalance -= $principal;
        }

        return response()->json(['success' => true, 'message' => 'Loan disbursed and EMI schedule created']);
    }
}