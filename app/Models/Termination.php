<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termination extends Model
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
        'termination_type_id',
        'termination_reason_id',
        'date_of_termination',
        'joining_date',
        'final_paycheck_date',
        'final_paycheck_amount',
        'unused_vacation_compensation_amount',
        'unused_sick_leave_compensation_amount',
        'severance_pay_amount',
        'benefits_termination_date',
        'continuation_of_benefits_offered',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function terminationType()
    {
        return $this->belongsTo(TerminationType::class);
    }

    public function terminationReason()
    {
        return $this->belongsTo(TerminationReason::class);
    }
}
