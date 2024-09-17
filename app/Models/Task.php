<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =[
        'manager_id',
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'observation',
        'to_assigned'
    ];
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function activity(){
        return $this->hasMany(Activity::class);
    }
    // public function scopePriority($query , $priority){
    //     $query->where('priority',$priority);
    // }
    // public function scopeStatus($query , $status){
    //     $query->where('status', $status);
    // }
}
