<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'leads';
    protected $primaryKey = 'lead_id';
    public $incrementing = true;

    protected $fillable = [
        'lead_id', 'branch_id', 'branch_name', 'employee_id', 'employee_name', 'email',
        'mobile_no', 'alt_mobile_no', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'pan_number', 'aadhar_number', 'kyc_status', 'status_of_enach',
        'residential_address', 'office_address', 'permanent_address', 'pin_code',
        'state', 'district', 'occupation_type', 'monthly_income', 'loan_demand_amount',
        'approved_loan_amount', 'interest_rate', 'tenure_months', 'processing_fees',
        'gst_on_processing_fees', 'insurance_charges', 'disbursement_amount', 'emi',
        'total_payment', 'total_paid_amount', 'penalty_total', 'penalty_paid',
        'emi_paid_count', 'total_emi_count', 'total_interest', 'interest_type',
        'rejection_reason', 'updated_by', 'branch_recommendation',
        'aadhar_card_image_front', 'aadhar_card_image_back', 'selfie_picture',
        'shop_business_image', 'bank_statement_pdf_path', 'disbursement_date',
        'loan_type', 'payment_mode', 'emi_date', 'bank_name', 'account_number',
        'ifsc_code', 'account_holder_name', 'bank_verification_status',
        'collection_assign_employee_id'
    ];

    protected $dates = ['disbursement_date', 'emi_date', 'created_at', 'updated_at'];

    // Relationships

    public function loanDetail()
    {
        return $this->hasOne(LoanDetail::class, 'lead_id', 'lead_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function collection_assign_employee()
    {
        return $this->belongsTo(User::class, 'collection_assign_employee_id');
    }
}
