<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
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
        "sender_id",
        "receiver_id",
        "business_id",
        "entity_name",
        "entity_id",
        "entity_ids",


        'notification_title',
        'notification_description',
        'notification_link',
        "is_system_generated",
        "notification_template_id",
        "status",

    ];

    protected $casts = [
        'entity_ids' => 'array',

    ];





    public function template(){
        return $this->belongsTo(NotificationTemplate::class,'notification_template_id', 'id');
    }
    public function sender(){
        return $this->belongsTo(User::class,'sender_id', 'id');
    }
    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id', 'id');
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id', 'id');
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
