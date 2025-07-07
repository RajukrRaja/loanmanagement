<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loan'; // Table name
    protected $primaryKey = 'loan_id'; // Primary key
    public $incrementing = true; // Auto-increment

    // Allow all fields to be mass assignable
    protected $guarded = [];

    // OR, if you prefer only specific fields (uncomment below and comment $guarded above):
    /*
    protected $fillable = [
        'loan_id',
        'lead_id',
        'branch_id',
        'branch_name',
        'employee_id',
        'employee_name',
        'email',
        'mobile_no',
        'alt_mobile_no',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'pan_number',
        'aadhar_number',
        'kyc_status',
        'loan_status',
        'status_of_enach',
        'residential_address',
        'office_address',
        'permanent_address',
        'pin_code',
        'state',
        'district',
        'occupation_type',
        'monthly_income',
        'loan_demand_amount',
        'approved_loan_amount',
        'interest_rate',
        'tenure_months',
        'processing_fees',
        'gst_on_processing_fees',
        'insurance_charges',
        'prepayment_charges',
        'disbursement_amount',
        'emi',
        'total_payment',
        'amount_paid',
        'amount_unpaid',
        'unpaid_principal',
        'unpaid_interest',
        'total_penalty',
        'paid_penalty',
        'remaining_penalty',
        'total_amount',
        'emi_status',
        'total_paid_amount',
        'penalty_total',
        'penalty_paid',
        'emi_paid_count',
        'total_emi_count',
        'total_interest',
        'interest_amount',
        'interest_type',
        'rejection_reason',
        'updated_by',
        'branch_recommendation',
        'aadhar_card_image_front',
        'aadhar_card_image_back',
        'selfie_picture',
        'shop_business_image',
        'bank_statement_pdf_path',
        'created_at',
        'updated_at',
        'disbursement_date',
        'loan_type',
        'total_loan',
        'disbursement_status',
        'payment_mode',
        'emi_date',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'bank_verification_status',
        'current_status', // ✅ Added here
        'gst_amount',
        'calculation_method',
    ];
    */

    // Relationships

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'lead_id');
    }



    public function emiSchedules()
    {
        return $this->hasMany(EmiSchedule::class, 'loan_id', 'loan_id');
    }
    
    public function foreclosePayments()
{
    return $this->hasMany(LoanPayment::class, 'loan_id')
                ->where('pay_type', 'forclose'); // spelling should match DB value
}

public function loanPayments()
{
    return $this->hasMany(\App\Models\LoanPayment::class, 'loan_id', 'loan_id');
}


}
