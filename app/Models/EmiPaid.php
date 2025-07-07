<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmiPaid extends Model
{
    protected $table = 'emi_paid';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lead_id',
        'sr_no',
        'emi_month',
        'emi_amount',
        'penalty_amount',
        'status',
        'expiry_date',
        'action_status',
        'txn_id',
        'payment_method',
        'payment_date',
    ];

    protected $casts = [
        'emi_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'expiry_date' => 'datetime',
        'payment_date' => 'datetime',
    ];
}