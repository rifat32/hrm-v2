<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
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
    protected $appends = ['total_candidates'];

    protected $fillable = [
        'title',
        'description',
        'required_skills',
        'application_deadline',
        'posted_on',
        'department_id',
        'minimum_salary',
        'maximum_salary',
        'experience_level',
        'job_type_id',
        'work_location_id',
        "is_active",
        "business_id",
        "created_by"

    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, "job_listing_id",'id');
    }

    // Define relationships with other tables
    public function job_type()
    {
        return $this->belongsTo(JobType::class, "job_type_id",'id');
    }

    public function work_location()
    {
        return $this->belongsTo(WorkLocation::class, "work_location_id" ,'id');
    }


    // Define relationships if needed

    public function job_platforms() {
        return $this->belongsToMany(JobPlatform::class, 'job_listing_job_platforms', 'job_listing_id', 'job_platform_id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, "department_id" , 'id');
    }

    public function getTotalCandidatesAttribute($value) {
           return $this->candidates()->count();
    }


    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }







    // public function getApplicationDeadlineAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getPostedOnAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

}
