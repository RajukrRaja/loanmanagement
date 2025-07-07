<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    protected $table = 'loan_details';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'lead_id', 'loan_type', 'payment_mode', 'disbursement_date', 'emi_date',
        'approved_loan_amount', 'disbursed_amount', 'amount_paid', 'amount_unpaid',
        'unpaid_principal', 'unpaid_interest', 'total_penalty', 'paid_penalty',
        'remaining_penalty', 'total_amount', 'interest', 'emi_status', 'created_at', 'updated_at'
    ];

    // Cast dates to Carbon instances
    protected $dates = ['disbursement_date', 'emi_date', 'created_at', 'updated_at'];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'lead_id');
    }

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id', 'id');
    }
}