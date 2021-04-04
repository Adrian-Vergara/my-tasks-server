<?php

namespace App\Models\Project;

use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "projects";
    protected $fillable = array('name', 'description', 'start_date', 'end_date', 'status', 'user_id');
    protected $dates = array("deleted_at");
    protected $hidden = array('updated_at', 'deleted_at');

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function statuses()
    {
        return $this->hasMany(ProjectStatus::class);
    }
}
