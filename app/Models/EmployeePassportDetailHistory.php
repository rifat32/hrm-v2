<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class EmployeePassportDetailHistory extends Model
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
        "business_id",

        'passport_number',
        "passport_issue_date",
        "passport_expiry_date",
        "place_of_issue",


        "from_date",
        "to_date",
        "user_id",
        "is_manual",
        'created_by'
    ];

    protected $appends = ['is_current'];
    public function getIsCurrentAttribute() {
        $current_passport_id = Session::get('current_passport_id');
        return $current_passport_id === $this->id;
    }



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by',"id");
    }

    public function employee(){
        return $this->hasOne(User::class,'id', 'user_id');
    }


}
