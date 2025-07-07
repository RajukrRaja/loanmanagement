<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description'];

    /**
     * Define the relationship with RoleDropdownPermission.
     */
    public function permissions()
    {
        return $this->hasMany(RoleDropdownPermission::class, 'role_id', 'id');
    }
}