<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'project_manager_id'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class,'project_user')
                    ->withPivot('role','contribution_hours');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function activity(){
        return $this->hasMany(Activity::class);
    }
    public function lastTask(){
        return $this->hasOne(Task::class)->latestOfMany('created_at','updated_at');
    }
    public function oldTask(){
        return $this->hasOne(Task::class)->oldestOfMany(['created_at','updated_at']);
    }
    public function highestPriorityWithCondition(string $titleCondition){

        return $this->hasOne(Task::class)->ofMany([
            'priority'=>'max'
        ], function(Builder $query) use($titleCondition){
            $query->where('title', 'LIKE', '%' . $titleCondition . '%');
        })->first();

    }
}
