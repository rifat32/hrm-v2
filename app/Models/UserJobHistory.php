<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJobHistory extends Model
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
        'user_id',
        'company_name',
        'country',
        'job_title',
        'employment_start_date',
        'employment_end_date',
        'responsibilities',
        'supervisor_name',
        'contact_information',
        'work_location',
        'achievements',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }


    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

    // public function getEmploymentStartDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getEmploymentEndDateAttribute($value)
    // {
    //     if(empty($value)) {
    //          return NULL;
    //     }
    //     return (new Carbon($value))->format('d-m-Y');
    // }






}
