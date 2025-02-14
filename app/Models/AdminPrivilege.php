<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPrivilege extends Model
{
    // Specify the table if it doesn't follow Laravel's naming convention.
    protected $table = 'admins_privileges';

    // Allow mass assignment on these fields.
    protected $fillable = ['admin_id', 'privilege_id'];

    /**
     * Get the admin that owns this privilege.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the privilege associated with the admin.
     */
    public function privilege()
    {
        return $this->belongsTo(Privilege::class, 'privilege_id');
    }
}
