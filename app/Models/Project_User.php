<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_User extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'project_id',
        'role',
        'contribution_hours',
        'last_activity',
    ];
    protected $table = 'project_user';
}
