<?php

namespace App\Models\Project;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "project_statuses";
    protected $fillable = array('project_id', 'user_id', 'status');
    protected $dates = array("deleted_at");
    protected $hidden = array('updated_at', 'deleted_at');

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
