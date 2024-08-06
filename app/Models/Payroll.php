<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
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
        "payroll_name",

        'user_id',
        "payrun_id",

        'total_holiday_hours',
        'total_paid_leave_hours',
        'total_regular_attendance_hours',
        'total_overtime_attendance_hours',
        'regular_hours',
        'overtime_hours',
        'holiday_hours_salary',
        'leave_hours_salary',
        'regular_attendance_hours_salary',
        'overtime_attendance_hours_salary',


        'regular_hours_salary',
        'overtime_hours_salary',




        "start_date",
        "end_date",

        'status',
        'is_active',
        'business_id',
        'created_by',
    ];










    protected $casts = [
        'is_active' => 'boolean',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payrun()
    {
        return $this->belongsTo(Payrun::class, 'payrun_id');
    }

    public function payroll_attendances()
    {
        return $this->hasMany(PayrollAttendance::class, "payroll_id" ,'id');
    }

    public function payroll_leave_records()
    {
        return $this->hasMany(PayrollLeaveRecord::class, "payroll_id" ,'id');
    }

     public function payroll_holidays()
    {
        return $this->hasMany(PayrollHoliday::class, "payroll_id" ,'id');
    }



    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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
