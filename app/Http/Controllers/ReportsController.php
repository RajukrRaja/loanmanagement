<?php
namespace App\Http\Controllers;

use App\Models\DisbursementReport;
use App\Models\LoanDetail;
use App\Models\LoanPayment;
use App\Models\Loan;
use App\Models\Branch;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class ReportsController extends Controller
{
public function disbandmentReport(Request $request)
{
    $tillDate = $request->input('till_date') ? \Carbon\Carbon::parse($request->input('till_date')) : now();
    $branchId = $request->input('branch_id');
    $branches = \App\Models\Branch::all();
    $search = $request->input('search');

    $loans = \App\Models\Loan::query()
        ->where(function ($query) {
            $query->where('loan_status', 'disbursed')
                  ->orWhere('current_status', 'ongoing');
        })
        ->when($branchId, function ($query) use ($branchId, $branches) {
            $branchName = $branches->find($branchId)->branch_name ?? '';
            if ($branchName) {
                $query->where('branch_name', $branchName);
            }
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->whereDate('disbursement_date', '<=', $tillDate)
        ->paginate(10);

    return view('reports.disbandment', compact('loans', 'branches', 'tillDate', 'branchId', 'search'));
}


public function ongoingLoanReport(Request $request)
{
    $tillDate = $request->input('till_date') ? \Carbon\Carbon::parse($request->input('till_date')) : now();
    $branchId = $request->input('branch_id');
    $search = $request->input('search');

    $branches = \App\Models\Branch::all();

    $loans = \App\Models\Loan::query()
        ->where(function ($query) {
            $query->whereRaw("LOWER(TRIM(current_status)) = 'ongoing'")
                  ->orWhereHas('loanPayments', function ($q) {
                      $q->where('status', '!=', 'Paid');
                  });
        })
        ->when($branchId, function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->where(function ($q) use ($tillDate) {
            $q->whereDate('disbursement_date', '<=', $tillDate)
              ->orWhereNull('disbursement_date');
        })
        ->paginate(10);

    return view('reports.ongoing', compact('loans', 'branches', 'tillDate', 'branchId', 'search'));
}






public function closedLoanReport(Request $request)
{
    $tillDate = $request->input('till_date') 
        ? Carbon::parse($request->input('till_date')) 
        : now();

    $branchId = $request->input('branch_id');
    $search = $request->input('search');
    $branches = Branch::all();

    // Loans where all EMIs are paid and pay_type = 'normal'
    $closedLoans = Loan::query()
        ->whereNotNull('disbursement_date')
        ->whereDate('disbursement_date', '<=', $tillDate)
        ->whereHas('loanPayments', function ($query) {
            $query->where('pay_type', 'normal'); // ensure it has at least one normal EMI
        })
        ->whereDoesntHave('loanPayments', function ($query) {
            $query->where(function ($q) {
                $q->where('status', '!=', 'Paid')
                  ->orWhere('pay_type', '!=', 'normal'); // exclude any non-normal or unpaid EMIs
            });
        })
        ->when($branchId, function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('disbursement_date')
        ->paginate(10);

    // Same filters for the export version
    $loans = Loan::query()
        ->whereNotNull('disbursement_date')
        ->whereDate('disbursement_date', '<=', $tillDate)
        ->whereHas('loanPayments', function ($query) {
            $query->where('pay_type', 'normal');
        })
        ->whereDoesntHave('loanPayments', function ($query) {
            $query->where(function ($q) {
                $q->where('status', '!=', 'Paid')
                  ->orWhere('pay_type', '!=', 'normal');
            });
        })
        ->when($branchId, function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderByDesc('disbursement_date')
        ->get();

    return view('reports.closed', compact(
        'closedLoans',
        'loans',
        'branches',
        'tillDate',
        'branchId',
        'search'
    ));
}



public function allPaidEmiReport(Request $request)
{
    $tillDate = $request->input('till_date')
        ? Carbon::parse($request->input('till_date'))
        : now();

    $branchId = $request->input('branch_id');
    $search = $request->input('search');

    $branches = Branch::all();

    $emiPayments = LoanPayment::query()
        ->whereRaw("LOWER(TRIM(status)) = 'paid'")
        ->whereRaw("LOWER(TRIM(pay_type)) = 'normal'") // ✅ Filter for normal pay_type only
        ->whereDate('created_at', '<=', $tillDate)
        ->when($branchId, function ($query) use ($branchId) {
            $query->whereHas('loan.lead', function ($subQuery) use ($branchId) {
                $subQuery->where('branch_id', $branchId);
            });
        })
        ->when($search, function ($query) use ($search) {
            $query->whereHas('loan.lead', function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('mobile_no', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->with(['loan.lead.branch'])
        ->orderByDesc('created_at')
        ->paginate(10);

    return view('reports.paid_emis', [
        'emiPayments' => $emiPayments,
        'branches' => $branches,
        'tillDate' => $tillDate,
        'branchId' => $branchId,
        'search' => $search
    ]);
}


public function overdueEmiReport(Request $request)
{
    $tillDate = $request->input('till_date') 
        ? Carbon::parse($request->input('till_date')) 
        : now();

    $branchId = $request->input('branch_id');
    $search = $request->input('search');
    $branches = Branch::all();

    // Fetch all overdue EMIs before today
    $overdueEmis = LoanPayment::with([
            'loan.lead.employee',
            'loan.lead.branch',
            'loan.lead.collection_assign_employee'
        ])
        ->where('status', 'Overdue')
        ->where('emi_date', '<', now())
        ->whereHas('loan.lead', function ($q) use ($tillDate, $branchId, $search) {
            $q->whereDate('disbursement_date', '<=', $tillDate);

            if ($branchId) {
                $q->where('branch_id', $branchId);
            }

            if ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('mobile_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            }
        })
        ->orderBy('emi_date', 'asc')
        ->get();

    // Group by loan_id
    $grouped = $overdueEmis->groupBy('loan_id');

    // Only include loans with 2 or fewer overdue EMIs
    $filteredGrouped = $grouped->filter(function ($payments) {
        return $payments->count() <= 2;
    });

    // Prepare the report data
    $reports = $filteredGrouped->map(function ($payments, $loanId) {
        $firstPayment = $payments->first();
        $loan = $firstPayment->loan;
        $lead = $loan->lead;

        return (object) [
            'loan_id' => $loan->loan_id ?? 'N/A',
            'branch_name' => $lead->branch->branch_name ?? 'N/A',
            'employee_name' => $lead->employee->name ?? 'N/A',
            'collection_assign_employee_name' => optional($lead->collection_assign_employee)->name ?? 'N/A',
            'user_name' => trim(($lead->first_name ?? '') . ' ' . ($lead->last_name ?? '')),
            'mobile_number' => $lead->mobile_no ?? 'N/A',
            'email_id' => $lead->email ?? 'N/A',
            'address' => $lead->residential_address ?? 'N/A',
            'approved_loan_amount' => round((float) ($loan->approved_loan_amount ?? 0)),

            'due_emi' => $payments->count(),
            'emi_amount' => round($payments->sum('amount')),
            'half_paid_amount' => round($payments->sum('half_paid_amount')),
            'penalty' => round($payments->sum('penalty_amount')),
            'total_overdue' => round(
                $payments->sum('amount') + $payments->sum('penalty_amount') - $payments->sum('half_paid_amount')
            ),

            'last_due_emi_date' => optional($payments->max('emi_date'))->format('d/m/Y'),
            'emi_update' => optional($firstPayment->updated_at)->format('d/m/Y'),
            'status' => 'Overdue',
        ];
    })->values(); // Reset collection keys

    // Paginate manually
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = $reports->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedReports = new LengthAwarePaginator(
        $currentItems,
        $reports->count(),
        $perPage,
        $currentPage,
        ['path' => url()->current()]
    );

    return view('reports.overdue_emis', [
        'reports' => $paginatedReports,
        'branches' => $branches,
    ]);
}

public function overdueEmiReportNpa(Request $request)
{
    $tillDate = $request->input('till_date') 
        ? Carbon::parse($request->input('till_date')) 
        : now();

    $branchId = $request->input('branch_id');
    $search = $request->input('search');
    $branches = Branch::all();

    // Step 1: Fetch all EMIs that are past due and not paid
    $dueEmis = LoanPayment::with([
            'loan.lead.employee', 
            'loan.lead.branch', 
            'loan.lead.collection_assign_employee'
        ])
        ->where('emi_date', '<', now())
        ->whereIn('status', ['Overdue', 'NPA']) // ✅ Only unpaid overdue EMIs
        ->whereHas('loan.lead', function ($q) use ($tillDate, $branchId, $search) {
            $q->whereDate('disbursement_date', '<=', $tillDate);

            if ($branchId) {
                $q->where('branch_id', $branchId);
            }

            if ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('mobile_no', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            }
        })
        ->orderBy('emi_date', 'asc')
        ->get();

    // Step 2: Group by loan_id
    $grouped = $dueEmis->groupBy('loan_id');

    // Step 3: Filter only loans with more than 2 due EMIs
    $filteredGrouped = $grouped->filter(function ($payments) {
        return $payments->count() > 2;
    });

    // Step 4: Prepare report
    $reports = $filteredGrouped->map(function ($payments) {
        $firstPayment = $payments->first();
        $loan = $firstPayment->loan;
        $lead = $loan->lead;

        return (object) [
            'loan_id' => $loan->loan_id ?? 'N/A',
            'branch_name' => $lead->branch->branch_name ?? 'N/A',
            'employee_name' => $lead->employee->name ?? 'N/A',
            'collection_assign_employee_name' => optional($lead->collection_assign_employee)->name ?? 'N/A',
            'user_name' => trim(($lead->first_name ?? '') . ' ' . ($lead->last_name ?? '')),
            'mobile_number' => $lead->mobile_no ?? 'N/A',
            'email_id' => $lead->email ?? 'N/A',
            'address' => $lead->residential_address ?? 'N/A',
            'approved_loan_amount' => round((float) ($loan->approved_loan_amount ?? 0)),

            'due_emi' => $payments->count(),
            'emi_amount' => round($payments->sum('amount')),
            'half_paid_amount' => round($payments->sum('half_paid_amount')),
            'penalty' => round($payments->sum('penalty_amount')),
            'total_overdue' => round(
                $payments->sum('amount') + $payments->sum('penalty_amount') - $payments->sum('half_paid_amount')
            ),

            'last_due_emi_date' => optional($payments->max('emi_date'))->format('d/m/Y'),
            'emi_update' => optional($firstPayment->updated_at)->format('d/m/Y'),
            'status' => $firstPayment->status ?? 'N/A',
        ];
    })->values();

    // Step 5: Paginate
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentItems = $reports->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedReports = new LengthAwarePaginator(
        $currentItems,
        $reports->count(),
        $perPage,
        $currentPage,
        ['path' => url()->current()]
    );

    return view('reports.overdue_emis_npa', [
        'reports' => $paginatedReports,
        'branches' => $branches,
    ]);
}



public function outstandingemisReport(Request $request)
{
    $search = $request->query('search');

    $query = Loan::where('current_status', 'ongoing'); // Only ongoing loans

    // Optional search filter
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('loan_id', 'like', "%$search%")
              ->orWhere('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('mobile_no', 'like', "%$search%");
        });
    }

    $reports = $query->paginate(10); // Paginate as $reports

    return view('reports.outstanding_emi', compact('reports')); // Use the same name as in Blade
}




public function HalfPaymentReport()
{
    $loans = Loan::select([
            'loan.lead_id',
            'loan.branch_name',
            'loan.loan_id',
            'loan.employee_name',
            'loan.first_name',
            'loan.last_name',
            'loan.mobile_no',
            'loan.email',
            'loan.disbursement_date',
            'loan.approved_loan_amount',

            // Total principal paid
            DB::raw('(
                SELECT COALESCE(SUM(lp.principal_paid), 0)
                FROM loan_payments lp
                WHERE lp.loan_id = loan.loan_id
                AND lp.status = "Paid"
            ) as total_principal_paid'),

            // Total partial (half) payment paid
            DB::raw('(
                SELECT COALESCE(SUM(lp.half_payment_paid), 0)
                FROM loan_payments lp
                WHERE lp.loan_id = loan.loan_id
                AND lp.status = "Unpaid"
                AND lp.half_payment_paid > 0
            ) as total_partial_paid'),

            // Total rest amount fetched (not calculated)
            DB::raw('(
                SELECT COALESCE(SUM(lp.rest_amount), 0)
                FROM loan_payments lp
                WHERE lp.loan_id = loan.loan_id
                AND lp.status = "Unpaid"
                AND lp.half_payment_paid > 0
            ) as total_rest_amount')
        ])
        ->whereNotNull('loan.approved_loan_amount')
        ->where('loan.approved_loan_amount', '>', 0)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('loan_payments')
                ->whereColumn('loan_payments.loan_id', 'loan.loan_id')
                ->where('loan_payments.status', 'Unpaid')
                ->where('loan_payments.half_payment_paid', '>', 0);
        })
        ->paginate(10);

    // Add derived calculations + rest_amount logging
    $loans->getCollection()->transform(function ($loan) {
        $loan->total_paid = $loan->total_principal_paid + $loan->total_partial_paid;
        $loan->unpaid_principal = $loan->approved_loan_amount - $loan->total_paid;

        // Log EMIs where half_payment_paid exists but rest_amount is missing or zero
        $badEmis = DB::table('loan_payments')
            ->where('loan_id', $loan->loan_id)
            ->where('status', 'Unpaid')
            ->where('half_payment_paid', '>', 0)
            ->where('rest_amount', '<=', 0)
            ->get();

        if ($badEmis->isNotEmpty()) {
            Log::warning("Missing or invalid rest_amount for loan_id: {$loan->loan_id}", [
                'emi_numbers_with_issue' => $badEmis->pluck('emi_number'),
            ]);
        }

        return $loan;
    });

    return view('reports.HalfPayment_emi', compact('loans'));
}





public function ForclosureReport()
{
    $loans = Loan::whereHas('loanPayments', function ($query) {
            $query->where('pay_type', 'foreclose');
        })
        ->with([
            'loanPayments' => function ($query) {
                $query->where('pay_type', 'foreclose')
                    ->select([
                        'loan_id',
                        'emi_number',
                        'amount',
                        'principal_paid',
                        'interest_paid',
                        'emi_date',
                        'paid_at',
                        'pay_type',
                        'closure_amount'
                    ]);
            },
            'lead' // include lead relationship to access employee_name
        ])
        ->select([
            'lead_id',
            'branch_name',
            'loan_id',
            'first_name',
            'last_name',
            'mobile_no',
            'email',
            'disbursement_date',
            'approved_loan_amount',
            'closed_on as closure_date'
        ])
        ->paginate(10);

    $loans->getCollection()->transform(function ($loan) {
        $loan->closure_amount = $loan->loanPayments->where('closure_amount', '>', 0)->first()->closure_amount ?? null;
        $loan->employee_name = $loan->lead->employee_name ?? 'N/A'; // access employee_name
        return $loan;
    });

    return view('reports.Forclosure_emi', compact('loans'));
}



}

?>