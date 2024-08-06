<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
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
    protected $appends = ['can_delete'];
    protected $fillable = [
        'user_id',
        'name',
        'code',
        'serial_number',
        'type',
        "is_working",
        "status",
        'image',
        'date',
        'note',
        "business_id",
        'created_by',
    ];

    public function getCanDeleteAttribute($value) {
        $request = request();
        // You can now use $currentRequest as the request object

        if(!auth()->user()->hasRole("business_owner") && auth()->user()->id != $this->created_by) {
                return 0;
        }
        return 1;

        }

    // Relationships
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




    // public function getDateAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }




}
