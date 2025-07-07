<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DisbursementReport extends Model
{
    protected $table = 'disbursement_reports';
    protected $fillable = [
        'user_id', 'branch_name', 'employee_name', 'loan_id', 'mobile_number', 'email_id',
        'current_address', 'account_number', 'ifsc', 'bank_name', 'tenure', 'loan_amount',
        'interest_amount', 'processing_fees', 'disbursement_amount', 'disbursement_date'
    ];
}