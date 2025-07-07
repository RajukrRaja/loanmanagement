<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources';

    protected $fillable = [
        'name',
        'description',
    ];

    // Optional: Define relationship to Permissions
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
