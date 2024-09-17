<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'content',
        'duration_hours'

    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function task(){
        return $this->belongsTo(Task::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
}
