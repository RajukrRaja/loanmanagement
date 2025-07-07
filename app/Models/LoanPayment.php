<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $table = 'loan_payments';
    protected $guarded = [];

    protected $fillable = [
        'loan_id',
        'emi_number',
        'amount',
        'principal_paid',
        'interest_paid',
        'emi_date',
        'status',
        'penalty_amount',
        'is_first_time_overdue',
        'fixed_penalty_applied',
        'last_penalty_date',
        'payment_method',
        'payment_reference',
        'paid_at',
        'emi_payment_date',
        'half_payment_paid',
        'pay_type',
        'rest_amount', // Stored column as per SQL output
    ];

    protected $casts = [
        'emi_number' => 'integer', // Added for consistency (e.g., 1, 2, 3 in SQL output)
        'amount' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'emi_date' => 'datetime',
        'status' => 'string',
        'penalty_amount' => 'decimal:2',
        'is_first_time_overdue' => 'boolean',
        'fixed_penalty_applied' => 'boolean',
        'last_penalty_date' => 'datetime',
        'payment_method' => 'string',
        'payment_reference' => 'string',
        'paid_at' => 'datetime',
        'emi_payment_date' => 'datetime',
        'half_payment_paid' => 'decimal:2',
        'pay_type' => 'string', // Added for foreclosePayments relationship
        'rest_amount' => 'decimal:2', // Added for stored column
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'loan_id');
    }

    public function foreclosePayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id')
                    ->where('pay_type', 'foreclose')
                    ->select([
                        'loan_id',
                        'emi_number',
                        'amount',
                        'payment_method',
                        'pay_type',
                        'rest_amount',
                        'half_payment_paid',
                        'status',
                        'emi_payment_date',
                        'penalty_amount',
                    ]);
    }
}