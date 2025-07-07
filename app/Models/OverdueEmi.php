<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OverdueEmi extends Model
{
    protected $table = 'overdue_emis';
    protected $fillable = [
        'loan_id', 'branch_name', 'employee_name', 'collection_assign_employee_name', 'user_name',
        'mobile_number', 'email_id', 'address', 'loan_amount', 'due_emi', 'emi_amount',
        'half_paid_amount', 'penalty', 'total_overdue', 'last_due_emi_date', 'emi_update', 'status'
    ];
}