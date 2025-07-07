<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(DropdownItem::class, 'dropdown_id');
    }
}