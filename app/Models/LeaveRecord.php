<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRecord extends Model
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
        'leave_id',
        'date',
        'start_time',
        'end_time',
        "capacity_hours",
        "leave_hours",

    ];
    public function getDurationAttribute()
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        // Calculate the difference in hours
        return $startTime->diffInHours($endTime);
    }
    public function leave(){
        return $this->belongsTo(Leave::class,'leave_id', 'id');
    }

    public function arrear(){
        return $this->hasOne(LeaveRecordArrear::class,'leave_record_id', 'id');
    }
    public function payroll_leave_record()
    {
        return $this->hasOne(PayrollLeaveRecord::class, "leave_record_id" ,'id');
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }








    // public function getDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }










}
