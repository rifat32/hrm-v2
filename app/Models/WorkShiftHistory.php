<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShiftHistory extends Model
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
        'name',
        "break_type",
        "break_hours",
        'type',
        "description",

        'is_business_default',
        'is_personal',



        "is_default",
        "is_active",
        "business_id",
        "created_by",



        "from_date",
        "to_date",
        "work_shift_id",


    ];

    protected $dates = ['start_date',
    'end_date'];


    public function details(){
        return $this->hasMany(WorkShiftDetailHistory::class,'work_shift_id', 'id');
    }

    public function departments() {
        return $this->belongsToMany(Department::class, 'employee_department_work_shift_histories', 'work_shift_id', 'department_id');
    }


    public function users() {
        return $this->belongsToMany(User::class, 'employee_user_work_shift_histories', 'work_shift_id', 'user_id')->withPivot('from_date', 'to_date');
    }

    public function user_work_shift(){
        return $this->hasMany(EmployeeUserWorkShiftHistory::class,'work_shift_id', 'id');
    }




}
