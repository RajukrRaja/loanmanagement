<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $primaryKey = 'branch_id'; // Matches the PK in the branches table
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps are present in the table, so leave default true
    protected $fillable = ['branch_name', 'parent_branch_id', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }
}