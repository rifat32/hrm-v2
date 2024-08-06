<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reminder extends Model
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
        'title',
        'model_name',
        "issue_date_column",
        'expiry_date_column',
        "user_eligible_field",
        "user_relationship",
        'duration',
        'duration_unit',
        'send_time',
        'frequency_after_first_reminder',
        'reminder_limit',
        'keep_sending_until_update',
        'entity_name',
        "business_id",
        "created_by"
    ];

    protected $casts = [
        'keep_sending_until_update' => 'boolean',
    ];
    protected $hidden = [
        'model_name',
        "issue_date_column",
        'expiry_date_column',
    ];
}
