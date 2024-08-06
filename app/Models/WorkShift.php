<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
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
        "created_by"
    ];

    protected $dates = ['start_date',
    'end_date',];







    public function details(){
        return $this->hasMany(WorkShiftDetail::class,'work_shift_id', 'id');
    }







    public function departments() {
        return $this->belongsToMany(Department::class, 'department_work_shifts', 'work_shift_id', 'department_id');
    }

    public function work_locations() {
        return $this->belongsToMany(WorkLocation::class, 'work_shift_locations', 'work_shift_id', 'work_location_id');
    }












    public function users() {
        return $this->belongsToMany(User::class, 'user_work_shifts', 'work_shift_id', 'user_id');
    }






}
