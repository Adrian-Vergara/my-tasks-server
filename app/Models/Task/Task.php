<?php

namespace App\Models\Task;

use App\Models\Project\Project;
use App\Models\Project\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tasks";
    protected $fillable = array('name', 'description', 'execution_date', 'status', 'project_id', 'user_id');
    protected $dates = array("deleted_at");
    protected $hidden = array('updated_at', 'deleted_at');

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasMany(TaskStatus::class);
    }

}
