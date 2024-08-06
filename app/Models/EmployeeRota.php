<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRota extends Model
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

        "description",




        "department_id",
        "user_id",


        "is_default",
        "is_active",
        "business_id",
        "created_by"
    ];


    protected $dates = [
    'start_date',
    'end_date'
];



    public function details(){
        return $this->hasMany(EmployeeRotaDetail::class,'employee_rota_id', 'id');
    }


    public function department() {
        return $this->belongsTo(Department::class,  'department_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class,  'user_id', 'id');
    }






}
