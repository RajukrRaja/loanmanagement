<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AllPaidEmi extends Model
{
    protected $table = 'all_paid_emis';
    protected $fillable = [
        'user_name', 'loan_id', 'emi_amount', 'principal_paid', 'interest_paid', 'emi_date',
        'emi_payment_date', 'paid_amount', 'payment_mode', 'loan_type', 'loan_officer_name'
    ];
}