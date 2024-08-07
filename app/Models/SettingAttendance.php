<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingAttendance extends Model
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
        'punch_in_time_tolerance',
        'work_availability_definition',
        'punch_in_out_alert',
        'punch_in_out_interval',
        'alert_area',
        'auto_approval',
        "is_geolocation_enabled",

        'service_name',
        'api_key',

        "business_id",
        "is_active",
        "is_default",
        "created_by"
    ];


    protected $casts = [
        'alert_area' => 'array',

    ];

    public function special_users() {
        return $this->belongsToMany(User::class, 'setting_attendance_special_users', 'setting_attendance_id', 'user_id');
    }
    public function special_roles() {
        return $this->belongsToMany(Role::class, 'setting_attendance_special_roles', 'setting_attendance_id', 'role_id');
    }





    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
}
