<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ClosedLoan extends Model
{
    protected $table = 'closed_loans';
    protected $fillable = [
        'loan_id', 'user_name', 'mobile_number', 'email_id', 'loan_amount', 'total_paid_amount',
        'closed_date', 'profile_view_link'
    ];
}