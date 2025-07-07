<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleDropdownItem extends Model
{
    protected $fillable = ['role_id', 'dropdown_item_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function dropdownItem()
    {
        return $this->belongsTo(DropdownItem::class);
    }
}