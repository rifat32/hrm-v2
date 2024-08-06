<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
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
        'start_date',
        'end_date',
        'description',
        "is_active",
        "business_id",
        "created_by"
    ];
    protected $appends = ['status'];


    public function getStatusAttribute($value) {


    $user_announcement = UserAnnouncement::where([
        "user_id" => auth()->user()->id,
        "announcement_id" =>$this->id
    ])
    ->first();
    if(!$user_announcement) {
return "invalid";
    }else {
        return $user_announcement->status;
    }



        }



    public function creator() {
        return $this->belongsTo(User::class, "created_by","id");
    }

    public function departments() {
        return $this->belongsToMany(Department::class, 'department_announcements', 'announcement_id', 'department_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_announcements', 'announcement_id', 'user_id')->withPivot('status');
    }


}
