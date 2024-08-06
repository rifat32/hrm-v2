<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, DatabaseUtil;

     public function getConnectionName()
    {
        if (!empty(auth()->user()->business_id)) {
            $connection = 'business_' . auth()->user()->business_id;
            config(["database.connections.{$connection}" => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $connection,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]]);
            return $connection;
        }

        return $this->connection; // Default connection
    }


    protected $fillable = [
        "unique_identifier",
        'name',
        'description',

        'assets',
        'cover',

        'start_date',
        'due_date',
        'end_date',
        'status',
        'project_id',
        'parent_task_id',
        "task_category_id",

        'order_no',

        'assigned_by',
        "assigned_to",

        "is_active",
        "business_id",
        "created_by"
    ];



    protected $casts = [
        'assets' => 'array',

    ];


    public function labels() {
        return $this->belongsToMany(Label::class, 'task_labels', 'task_id', 'label_id');
    }



    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function task_category()
    {
        return $this->belongsTo(TaskCategory::class);
    }

    public function parent_task()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function assigned_by()
    {
        return $this->belongsTo(User::class, 'assigned_by','id');
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to','id');
    }



    public function assignees() {
        return $this->belongsToMany(User::class, 'task_assignees', 'task_id', 'assignee_id');
    }


    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }




    // public function getStartDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getEndDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getDueDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }




}
