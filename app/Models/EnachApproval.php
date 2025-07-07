<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnachApproval extends Model
{
    protected $table = 'enach_approvals';

    protected $primaryKey = 'id';

    protected $fillable = [
        'lead_id',
        'branch_id',
        'branch_name',
        'employee_id',
        'employee_name',
        'full_name',
        'email',
        'kyc_status',
        'status_of_enach',
        'loan_approved_amount',
        'branch_recommendation',
    ];

    protected $casts = [
        'loan_approved_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;
}