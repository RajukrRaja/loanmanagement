<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    /**
     * Fetch approved KYC users for the admin view.
     */
    public function getApprovedKycUsers(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $perPage = $request->query('per_page', 10);

            $query = Lead::query()->where('kyc_status', 'Approved');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('mobile_no', 'like', "%{$search}%")
                      ->orWhere('alt_mobile_no', 'like', "%{$search}%")
                      ->orWhere('branch_name', 'like', "%{$search}%")
                      ->orWhere('employee_name', 'like', "%{$search}%");
                });
            }

            $leads = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return view('superadmin.approvedKycUsers', compact('leads'));
        } catch (\Exception $e) {
            Log::error('Error fetching approved KYC users: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Failed to fetch approved KYC users.');
        }
    }

    /**
     * Fetch leads (API endpoint).
     */
    public function getLeads(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $sortField = $request->query('sort_field', 'lead_id');
            $sortOrder = $request->query('sort_order', 'asc');

            $validSortFields = [
                'lead_id', 'first_name', 'email', 'mobile_no', 'loan_demand_amount',
                'loan_approved_amount', 'kyc_status', 'status_of_enach', 'monthly_income',
                'loan_amount', 'interest_rate', 'tenure', 'disbursement_date'
            ];
            if (!in_array($sortField, $validSortFields)) {
                $sortField = 'lead_id';
            }

            $query = Lead::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('mobile_no', 'like', "%{$search}%")
                      ->orWhere('alt_mobile_no', 'like', "%{$search}%");
                });
            }

            $leads = $query->orderBy($sortField, $sortOrder)->get();

            $stats = [
                'total_leads' => $leads->count(),
                'new_leads' => $leads->where('created_at', '>=', now()->subDays(7))->count(),
                'approved_leads' => $leads->where('kyc_status', 'Approved')->count(),
                'new_approved' => $leads->where('kyc_status', 'Approved')
                    ->where('updated_at', '>=', now()->subDays(7))->count(),
            ];

            return response()->json([
                'leads' => $leads,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching leads: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
 * Store a new lead.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'mobile_no' => 'required|regex:/^[0-9]{10}$/',
        'alt_mobile_no' => 'nullable|regex:/^[0-9]{10}$/',
        'email' => 'nullable|email|max:255',
        'date_of_birth' => 'nullable|date',
        'pan_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
        'aadhar_number' => 'nullable|regex:/^[0-9]{12}$/',
        'residential_address' => 'nullable|string',
        'office_address' => 'nullable|string',
        'permanent_address' => 'nullable|string',
        'pin_code' => 'nullable|regex:/^[0-9]{6}$/',
        'state' => 'nullable|string|max:255',
        'district' => 'nullable|string|max:255',
        'occupation_type' => 'nullable|in:Salaried,Self-Employed,Student',
        'monthly_income' => 'nullable|numeric|min:0',
        'loan_demand_amount' => 'nullable|numeric|min:0',
        'loan_type' => 'nullable|string|max:255',
        'payment_mode' => 'nullable|string|max:255',
        'emi_date' => 'nullable|date',
        'status_of_enach' => 'nullable|in:approve,reject,pending',
        'branch_recommendation' => 'nullable|string|max:1000',
        'aadhar_card_image_front' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'aadhar_card_image_back' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'pan_card_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'selfie_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'shop_business_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'bank_statement_pdf_path' => 'nullable|file|mimes:pdf|max:5120',
        'bank_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:50',
        'ifsc_code' => 'nullable|string|max:11',
        'bank_verification_status' => 'nullable|in:Pending,Verified,Failed',
    ]);

    $user = Auth::user();
    if (!$user) {
        return redirect()->back()->with('error', 'Unable to create lead: No authenticated user.');
    }

    $branch_id = null;
    $branch_name = null;
    $employee_id = $user->id;
    $employee_name = $user->name;

    if ($user->branch_id) {
        $branch = Branch::find($user->branch_id);
        if ($branch) {
            $branch_id = $branch->branch_id;
            $branch_name = $branch->branch_name;
        }
    }

    $files = [
        'aadhar_card_image_front',
        'aadhar_card_image_back',
        'pan_card_image',
        'selfie_picture',
        'shop_business_image',
        'bank_statement_pdf_path'
    ];

    foreach ($files as $file) {
        if ($request->hasFile($file)) {
            $path = $request->file($file)->store('uploads/leads', 'public');
            $validated[$file] = $path;
        }
    }

    // Add metadata
    $validated['employee_id'] = $employee_id;
    $validated['employee_name'] = $employee_name;
    $validated['branch_id'] = $branch_id;
    $validated['branch_name'] = $branch_name;
    $validated['created_by'] = $user->id;

    try {
        // First, create the lead so it gets an auto-increment ID
        $lead = Lead::create($validated);

        // Now create formatted custom lead code
        $lead->lead_code = 'LEAD' . str_pad($lead->id, 6, '0', STR_PAD_LEFT);
        $lead->save();

        return redirect()->route('employee.ViewCreateLead')->with('success', 'Lead created successfully with ID ' . $lead->lead_code);
    } catch (\Exception $e) {
        Log::error('Failed to create lead: ' . $e->getMessage(), ['exception' => $e->getTraceAsString()]);
        return redirect()->back()->with('error', 'Failed to create lead: ' . $e->getMessage());
    }
}


    /**
     * Update KYC details for a lead.
     */
    public function updateKyc(Request $request, $lead_id)
    {
        $lead = Lead::where('lead_id', $lead_id)->firstOrFail();

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'mobile_no' => 'required|regex:/^[0-9]{10}$/',
            'alt_mobile_no' => 'nullable|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'pan_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'aadhar_number' => 'nullable|regex:/^[0-9]{12}$/',
            'residential_address' => 'nullable|string',
            'office_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'pin_code' => 'nullable|regex:/^[0-9]{6}$/',
            'state' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'occupation_type' => 'nullable|in:Salaried,Self-Employed,Student',
            'monthly_income' => 'nullable|numeric|min:0',
            'loan_demand_amount' => 'nullable|numeric|min:0',
            'loan_type' => 'nullable|string|max:255',
            'payment_mode' => 'nullable|string|max:255',
            'emi_date' => 'nullable|date',
            'status_of_enach' => 'nullable|in:approve,reject,pending',
            'branch_recommendation' => 'nullable|string|max:1000',
            'aadhar_card_image_front' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'aadhar_card_image_back' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'pan_card_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'selfie_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'shop_business_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'bank_statement_pdf_path' => 'nullable|file|mimes:pdf|max:5120',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:11',
            'bank_verification_status' => 'nullable|in:Pending,Verified,Failed',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::warning('Validation failed for updateKyc, lead_id: ' . $lead_id, [
                'errors' => $validator->errors()->all(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $files = [
            'aadhar_card_image_front',
            'aadhar_card_image_back',
            'pan_card_image',
            'selfie_picture',
            'shop_business_image',
            'bank_statement_pdf_path',
        ];

        foreach ($files as $field) {
            if ($request->hasFile($field)) {
                if (!empty($lead->$field)) {
                    Storage::disk('public')->delete($lead->$field);
                }
                $lead->$field = $request->file($field)->store('uploads/leads', 'public');
            }
        }

        $lead->fill($request->only([
            'first_name', 'middle_name', 'last_name', 'mobile_no', 'alt_mobile_no', 'email',
            'date_of_birth', 'pan_number', 'aadhar_number', 'residential_address',
            'office_address', 'permanent_address', 'pin_code', 'state', 'district',
            'occupation_type', 'monthly_income', 'loan_demand_amount', 'loan_type',
            'payment_mode', 'emi_date', 'status_of_enach', 'branch_recommendation',
            'bank_name', 'account_number', 'ifsc_code', 'bank_verification_status'
        ]));
        $lead->updated_by = Auth::id();
        $lead->save();

        return redirect()->route('employee.viewLeadDetails', $lead_id)->with('success', 'KYC details updated successfully.');
    }

 /**
 * Approve KYC for a lead.
 */
public function approveKyc(Request $request, $lead_id)
{
    $lead = Lead::where('lead_id', $lead_id)->firstOrFail();

    $rules = [
        'approved_loan_amount' => 'required|numeric|min:0',
        'interest_rate' => 'required|numeric|min:0',
        'tenure_months' => 'required|integer|min:1',
        'processing_fees' => 'nullable|numeric|min:0',
        'interest_type' => 'required|in:reduce,flat',
        'gst_on_processing_fees' => 'nullable|numeric|min:0',
        'insurance_charges' => 'nullable|numeric|min:0',
        'credit_report_charges' => 'nullable|numeric|min:0', // ✅ added
        'disbursement_amount' => 'nullable|numeric|min:0',
        'emi' => 'nullable|numeric|min:0',
        'total_payment' => 'nullable|numeric|min:0',
        'total_emi_count' => 'nullable|integer|min:0',
        'total_interest' => 'nullable|numeric|min:0',
        'disbursement_date' => 'nullable|date',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        Log::warning('Validation failed for approveKyc, lead_id: ' . $lead_id, [
            'errors' => $validator->errors()->all(),
            'request_data' => $request->all()
        ]);
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $lead->update([
            'kyc_status' => 'Approved',
            'approved_loan_amount' => $request->approved_loan_amount,
            'interest_rate' => $request->interest_rate,
            'tenure_months' => $request->tenure_months,
            'processing_fees' => $request->processing_fees,
            'interest_type' => $request->interest_type,
            'gst_on_processing_fees' => $request->gst_on_processing_fees,
            'insurance_charges' => $request->insurance_charges,
            'credit_report_charges' => $request->credit_report_charges, // ✅ added
            'disbursement_amount' => $request->disbursement_amount,
            'emi' => $request->emi,
            'total_payment' => $request->total_payment,
            'total_emi_count' => $request->total_emi_count,
            'total_interest' => $request->total_interest,
            'disbursement_date' => $request->disbursement_date,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()->route('employee.viewLeadDetails', $lead_id)->with('success', 'KYC approved successfully.');
    } catch (\Exception $e) {
        Log::error('Error approving KYC for lead_id: ' . $lead_id . ', Message: ' . $e->getMessage(), [
            'exception' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Failed to approve KYC: ' . $e->getMessage());
    }
}

public function rejectKyc(Request $request, $lead_id)
{
    $lead = Lead::where('lead_id', $lead_id)->firstOrFail();

    $request->validate([
        'reason' => 'required|string|max:500',
    ]);

    try {
        $lead->update([
            'kyc_status' => 'Rejected',
            'rejection_reason' => $request->reason,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);

        return redirect()->route('employee.viewLeadDetails', $lead_id)->with('success', 'KYC rejected successfully.');
    } catch (\Exception $e) {
        Log::error('Error rejecting KYC for lead_id: ' . $lead_id . ', Message: ' . $e->getMessage(), [
            'exception' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Failed to reject KYC.');
    }
}


/**
 * Verify bank details for a lead.
 */
public function verifyBank(Request $request, $lead_id)
{
    $lead = Lead::where('lead_id', $lead_id)->firstOrFail();

    $rules = [
        'account_holder_name'     => 'required|string|max:255',
        'bank_name'               => 'required|string|max:255',
        'account_number'          => 'required|string|max:50',
        'confirm_account_number'  => 'required|string|same:account_number',
        'ifsc_code'               => 'required|string|max:11|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
        'verification_status'     => 'required|in:Verified,Pending,Rejected',
        'rejection_reason'        => 'required_if:verification_status,Rejected|string|nullable|max:500',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        Log::warning('Validation failed for verifyBank, lead_id: ' . $lead_id, [
            'errors' => $validator->errors()->all(),
            'request_data' => $request->all()
        ]);
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $lead->update([
            'account_holder_name'       => $request->account_holder_name,
            'bank_name'                 => $request->bank_name,
            'account_number'            => $request->account_number,
            'ifsc_code'                 => $request->ifsc_code,
            'bank_verification_status'  => $request->verification_status,
            'rejection_reason'          => $request->verification_status === 'Rejected' ? $request->rejection_reason : null,
            'updated_by'                => Auth::id(),
            'updated_at'                => now(),
        ]);

        return redirect()->route('employee.viewLeadDetails', $lead_id)->with('success', 'Bank details verified successfully.');
    } catch (\Exception $e) {
        Log::error('Error verifying bank for lead_id: ' . $lead_id . ', Message: ' . $e->getMessage(), [
            'exception' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Failed to verify bank details.');
    }
}


    /**
     * Add a suggestion for a lead.
     */
    public function addSuggestion(Request $request, $lead_id)
    {
        $request->validate([
            'suggestion' => 'required|string|max:500',
        ]);

        Log::info("Suggestion added for Lead ID: {$lead_id}", [
            'employee_id' => Auth::id(),
            'suggestion' => $request->suggestion,
        ]);

        return response()->json(['message' => 'Suggestion added successfully']);
    }

    /**
     * Show the create lead form.
     */
    public function showCreateLeadForm()
    {
        return view('employee.employeeCreateLead');
    }

    /**
     * Show all leads.
     */
    public function showLead()
    {
        $leads = Lead::paginate(10);
        return view('employee.ViewCreateLead', compact('leads'));
    }

    /**
     * Show lead details.
     */
    public function showLeadDetails($lead_id)
    {
        $lead = Lead::where('lead_id', $lead_id)->firstOrFail();
        return view('employee.viewLeadDetails', compact('lead'));
    }
    
    

    /**
     * Generate a unique lead ID.
     */
    protected function generateLeadId()
    {
        $latestLead = Lead::orderBy('lead_id', 'desc')->first();
        $lastId = $latestLead ? intval(substr($latestLead->lead_id, 4)) : 0;
        return 'LEAD' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Fetch lead details for AJAX requests.
     */
    public function getLeadDetails($lead_id)
    {
        $lead = Lead::where('lead_id', $lead_id)->firstOrFail();
        return response()->json([
            'monthly_income' => $lead->monthly_income,
            'loan_demand_amount' => $lead->loan_demand_amount,
            'kyc_status' => $lead->kyc_status,
        ]);
    }
}