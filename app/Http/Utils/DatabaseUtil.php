<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait DatabaseUtil
{
    public function setConnectionForBusiness()
    {
        if (Auth::check()) {
            $businessId = Auth::user()->business_id;
            $connectionName = 'business_' . $businessId;

            config(["database.connections.{$connectionName}" => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $connectionName,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]]);

            DB::purge($connectionName);
            $this->setConnection($connectionName);
        }
    }
    

    public function createDatabase(){

        if(!empty(auth()->user()->business_id)) {
            $databaseName = 'business_' . auth()->user()->business_id;

            // Create the new database
            DB::statement("CREATE DATABASE {$databaseName}");

            // Optionally, run migrations on the new database
            Artisan::call('migrate', [
                '--database' => $databaseName,
                '--path' => 'database/migrations/business',
            ]);
        }


    }
}
