<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
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
        'name', 'description', 'start_date', 'end_date', 'repeats_annually',  'is_active', 'business_id', "status", "created_by",
    ];

    public function departments() {
        return $this->belongsToMany(Department::class, 'department_holidays', 'holiday_id', 'department_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_holidays', 'holiday_id', 'user_id');
    }


    public function creator() {
        return $this->belongsTo(User::class, "created_by","id");
    }

    public function payroll_holiday()
    {
        return $this->hasOne(PayrollHoliday::class, "holiday_id" ,'id');
    }




    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }




    public function getStartDateAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }
    public function getEndDateAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }






}
