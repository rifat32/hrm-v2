<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait DatabaseUtil
{
    public function setConnectionForBusiness()
    {

            if (!empty(auth()->user()->business_id)) {
                $businessId = auth()->user()->business_id;
                $connectionName = 'business_' . $businessId;

                try {
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


                } catch (\Exception $e) {
                    error_log('Database connection error: ' . $e->getMessage());
                      // Throw a more specific exception or handle the error gracefully
        throw new \Exception('Failed to set database connection for business');
                }
            }

    }


    public function createDatabase($business_id){

error_log("creating...............................");
        if(!empty($business_id)) {
            $databaseName = 'business_' . $business_id;

            // Create the new database
            DB::statement("CREATE DATABASE {$databaseName}");

            // Fetch MySQL credentials from .env
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');

            // Grant all privileges on the new database to the existing user
            DB::statement("GRANT ALL PRIVILEGES ON {$databaseName}.* TO '{$username}'@'localhost' IDENTIFIED BY '{$password}'");

            // Flush privileges to apply changes
            DB::statement("FLUSH PRIVILEGES");

            // Dynamically configure the new database connection
            config([
                'database.connections.business' => [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => $databaseName,
                    'username' => $username,
                    'password' => $password,
                    'unix_socket' => env('DB_SOCKET', ''),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => true,
                    'engine' => null,
                ],
            ]);

            // Optionally, run migrations on the new database
            Artisan::call('migrate', [
                '--database' => 'business',
                '--path' => 'database/migrations',
            ]);

            error_log("Database '{$databaseName}' created and migrations applied.");




        }


    }
}
