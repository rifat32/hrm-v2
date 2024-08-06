<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
class Candidate extends Model
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
        'email',
        'phone',
        'experience_years',
        'education_level',

        'cover_letter',
        'application_date',
        'interview_date',
        'feedback',
        'status',
        'job_listing_id',
        'attachments',

        "is_active",
        "business_id",
        "created_by"
    ];

    protected $casts = [
        'attachments' => 'array',
    ];



    public function recruitment_processes() {
        return $this->hasMany(CandidateRecruitmentProcess::class, 'candidate_id', 'id');
    }


    public function business()
    {
        return $this->belongsTo(JobListing::class, "business_id",'id');
    }


    public function job_listing()
    {
        return $this->belongsTo(JobListing::class, "job_listing_id",'id');
    }

    public function job_platforms() {
        return $this->belongsToMany(JobPlatform::class, 'candidate_job_platforms', 'candidate_id', 'job_platform_id');
    }

// Define your model properties and relationships here

protected static function boot()
{
    parent::boot();

    // Listen for the "deleting" event on the Candidate model
    static::deleting(function($item) {
        // Call the deleteFiles method to delete associated files
        $item->deleteFiles();
    });
}

/**
 * Delete associated files.
 *
 * @return void
 */



public function deleteFiles()
{
    // Get the file paths associated with the candidate
    $filePaths = $this->attachments;

    // Iterate over each file and delete it
    foreach ($filePaths as $filePath) {
        if (File::exists(public_path($filePath))) {
            Log::error("file deleted......");
            File::delete(public_path($filePath));
        } else {
            Log::error("file not found......");
        }
    }
}



}
