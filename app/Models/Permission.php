<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    protected $fillable = ['role_id', 'dropdown_item_id', 'can_create', 'can_read', 'can_update', 'can_delete'];
    public function role() {
        return $this->belongsTo(Role::class);
    }
    public function dropdownItem() {
        return $this->belongsTo(DropdownItem::class);
    }
}