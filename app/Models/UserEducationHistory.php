<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class UserEducationHistory extends Model
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
        'degree',
        'major',
        'school_name',
        'graduation_date',
        'start_date',

        'achievements',
        'description',
        'address',
        'country',
        'city',
        'postcode',
        'is_current',
        'created_by',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',

    ];


    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by',"id");
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }


    // public function getGraduationDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

    // public function getStartDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

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
              File::delete(public_path($filePath));
          }
      }

  }


}
