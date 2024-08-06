<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TerminationReason extends Model
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
        'description',
        "is_active",
        "is_default",
        "business_id",
        "created_by"
    ];

    public function terminations()
    {
        return $this->hasMany(Termination::class);
    }

    public function disabled()
    {
        return $this->hasMany(DisabledTerminationReason::class, 'termination_reason_id', 'id');
    }


    public function getIsActiveAttribute($value)
    {

        $is_active = $value;
        $user = auth()->user();

        if(empty($user->business_id)) {
            if(empty($this->business_id) && $this->is_default == 1) {
                if(!$user->hasRole("superadmin")) {
                    $disabled = $this->disabled()->where([
                        "created_by" => $user->id
                   ])
                   ->first();
                   if($disabled) {
                      $is_active = 0;
                   }
                }
               }


        } else {

            if(empty($this->business_id)) {
             $disabled = $this->disabled()->where([
                  "business_id" => $user->business_id
             ])
             ->first();
             if($disabled) {
                $is_active = 0;
             }

            }


        }




        return $is_active;
    }

    public function getIsDefaultAttribute($value)
    {

        $is_default = $value;
        $user = auth()->user();

        if(!empty($user->business_id)) {
            if(empty($this->business_id) || $user->business_id !=  $this->business_id) {
                  $is_default = 1;

               }

        }



        return $is_default;
    }

}

