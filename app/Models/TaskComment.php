<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $table = 'task_comments'; 
    
    protected $fillable = ['task_id', 'user_id', 'comment', 'created_at', 'updated_at'];
    
    // Relationship to the Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // <--- ADD THIS FUNCTION --->
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}