<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
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
        'title',
        'description',
        // 'hidden_note',
        'history',
        'created_by'
    ];


    public function mentions()
    {
        return $this->hasMany(UserNoteMention::class, 'user_note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by',"id");
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by',"id");
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }

    // public function getHiddenNoteAttribute()
    // {
    //     $authenticatedUserId = auth()->user()->id;

    //     // Check if either 'created_by' or 'user_id' matches the authenticated user's ID
    //     if ($this->created_by == $authenticatedUserId || $this->user_id == $authenticatedUserId) {
    //         return $this->attributes['hidden_note'];
    //     }

    //     return null;
    // }

  // Convert history from JSON to array
  public function getHistoryAttribute($value)
  {
      return $this->created_by == auth()->user()->id ? json_decode($value, true) : null;
  }

  // Convert history from array to JSON before saving
  public function setHistoryAttribute($value)
  {
      $this->attributes['history'] = json_encode($value);
  }

  // Function to update history when saving changes
  public function updateHistory(array $changes)
  {
      // Exclude 'hidden_note' from changes
    //   unset($changes['hidden_note']);

      $history = $this->history ?? [];
      $history[] = $changes;
      $this->update(['history' => $history]);
  }


}
