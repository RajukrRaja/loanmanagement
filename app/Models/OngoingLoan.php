<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OngoingLoan extends Model
{
    protected $table = 'ongoing_loans';
    protected $fillable = [
        'branch_name', 'loan_id', 'user_name', 'mobile_number', 'email_id', 'disbursement_date',
        'loan_amount', 'tenure', 'remaining_principal', 'total_interest', 'remaining_interest',
        'overdue_amount'
    ];
}