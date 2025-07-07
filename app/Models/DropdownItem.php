<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownItem extends Model
{
    protected $fillable = ['dropdown_id', 'name', 'url', 'description'];

    /**
     * Get the dropdown that owns this item.
     */
    public function dropdown()
    {
        return $this->belongsTo(Dropdown::class);
    }

    /**
     * Get the permissions associated with this dropdown item.
     */
    public function actions()
    {
        return $this->hasMany(RoleDropdownPermission::class, 'dropdown_item_id');
    }
}