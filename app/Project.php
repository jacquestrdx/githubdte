<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'description', 'comment', 'due_date', 'user_id', 'project_id'
    ];

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
