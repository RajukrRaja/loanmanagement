<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmiSchedule extends Model
{
    protected $table = 'emi_schedule';

    protected $fillable = [
        'loan_id',
        'lead_id',
        'month',
        'emi_amount',
        'principal',
        'interest',
        'opening_balance',
        'closing_balance',
        'due_date',
        'status',
    ];

    protected $casts = [
        'emi_amount' => 'decimal:2',
        'principal' => 'decimal:2',
        'interest' => 'decimal:2',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
    
    public function emiSchedules()
{
    return $this->hasMany(EmiSchedule::class, 'loan_id', 'loan_id');
}

}