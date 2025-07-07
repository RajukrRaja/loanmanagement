<?php
// app/Models/RoleDropdownPermission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleDropdownPermission extends Model
{
    protected $table = 'role_dropdown_permissions';

    protected $fillable = [
        'role_id',
        'dropdown_item_id',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
        'can_view_lead',
        'can_approve_kyc',
        'can_reject_kyc',
        'description',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /** <-----------------  ADD THIS  -------------------- */
    public function dropdownItem()
    {
        return $this->belongsTo(DropdownItem::class, 'dropdown_item_id', 'id');
    }
}
