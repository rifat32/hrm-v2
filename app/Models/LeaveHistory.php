<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveHistory extends Model
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

        "leave_id",
        "actor_id",
        "action",
        "is_approved",
        "leave_created_at",
        "leave_updated_at",


        'leave_duration',
        'day_type',
        'leave_type_id',
        'user_id',
        'date',
        'note',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'attachments',
        "hourly_rate",
        "status",
        "is_active",
        "business_id",
        "created_by"
    ];

    protected $casts = [
        'attachments' => 'array',

    ];

    public function records(){
        return $this->hasMany(LeaveRecordHistory::class,'leave_id', 'id');
    }

    public function employee() {
        return $this->belongsTo(User::class, "user_id","id");
    }


    public function actor() {
        return $this->belongsTo(User::class, "actor_id","id");
    }
    public function approved_by_users(){
        return $this->hasMany(LeaveHistory::class,'leave_id', 'leave_id')
        ->where([
            "action" => "approve"
        ]);
    }
    public function rejected_by_users(){
        return $this->hasMany(LeaveHistory::class,'leave_id', 'leave_id')
        ->where([
            "action" => "reject"
        ]);
    }

    public function leave_type() {
        return $this->belongsTo(SettingLeaveType::class, "leave_type_id","id");
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

    // public function getStartDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getEndDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }



    // public function getLeaveCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getLeaveUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }


}
