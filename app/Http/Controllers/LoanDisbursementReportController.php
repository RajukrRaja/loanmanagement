<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LoanDetail;
use App\Models\LoanPayment;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanDisbursementReportController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $tillDate = $request->input('till_date') ? Carbon::parse($request->input('till_date')) : now();
        $branchId = $request->input('branch_id');
        $branches = Branch::all();

        // Disbursed Loans
        $disbursedLoans = Lead::with(['loanDetail', 'employee', 'branch'])
            ->where('kyc_status', 'Disburse')
            ->whereNotNull('disbursement_date')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->where('disbursement_date', '<=', $tillDate)
            ->get();

        // Active (Ongoing) Loans
        $activeLoans = LoanDetail::with(['lead.employee', 'lead.branch', 'loanPayments'])
            ->whereNotNull('disbursement_date')
            ->whereHas('loanPayments', fn($q) => $q->where('status', 'Unpaid')->orWhereNull('payment_date'))
            ->when($branchId, fn($q) => $q->whereHas('lead', fn($q2) => $q2->where('branch_id', $branchId)))
            ->where('disbursement_date', '<=', $tillDate)
            ->get();

        // Closed Loans
        $closedLoans = LoanDetail::with(['lead.employee', 'lead.branch'])
            ->whereNotNull('disbursement_date')
            ->whereRaw('emi_status LIKE ?', ['%/% Paid'])
            ->whereRaw('CAST(SUBSTRING_INDEX(emi_status, "/", 1) AS UNSIGNED) = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(emi_status, "/", -1), " ", 1) AS UNSIGNED)')
            ->when($branchId, fn($q) => $q->whereHas('lead', fn($q2) => $q2->where('branch_id', $branchId)))
            ->where('disbursement_date', '<=', $tillDate)
            ->get();

        // All Paid EMIs
        $paidEmis = LoanPayment::with(['loan.lead.employee', 'loan.lead.branch'])
            ->where('status', 'Paid')
            ->where('payment_date', '<=', $tillDate)
            ->when($branchId, fn($q) => $q->whereHas('loan.lead', fn($q2) => $q2->where('branch_id', $branchId)))
            ->get();

        // Overdue EMIs (up to 2)
        $overdueEmis = LoanPayment::with(['loan.lead.employee', 'loan.lead.branch'])
            ->where('status', 'Unpaid')
            ->where('emi_date', '<', now())
            ->whereHas('loan.lead', fn($q) => $q->where('disbursement_date', '<=', $tillDate)
                ->when($branchId, fn($q2) => $q2->where('branch_id', $branchId)))
            ->get()
            ->groupBy('loan_id')
            ->filter(fn($payments) => $payments->count() <= 2)
            ->flatten();

        // Overdue NPAs (3+)
        $overdueNpas = LoanPayment::with(['loan.lead.employee', 'loan.lead.branch'])
            ->where('status', 'Unpaid')
            ->where('emi_date', '<', now())
            ->whereHas('loan.lead', fn($q) => $q->where('disbursement_date', '<=', $tillDate)
                ->when($branchId, fn($q2) => $q2->where('branch_id', $branchId)))
            ->get()
            ->groupBy('loan_id')
            ->filter(fn($payments) => $payments->count() >= 3)
            ->flatten();

        return view('superadmin.loan_disbursement_report', compact(
            'disbursedLoans', 'activeLoans', 'closedLoans', 'paidEmis', 'overdueEmis', 'overdueNpas', 'branches', 'tillDate', 'branchId'
        ));
    }
}
