<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $primaryKey = 'audit_id';

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_value',
        'new_value',
    ];

    public $timestamps = false; // Disable timestamps (only created_at)

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
?>