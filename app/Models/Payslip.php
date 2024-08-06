<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
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
       'month',
       'year',
       'payment_method',

       'payment_amount',
       "payment_notes",
       'payment_date',
       'payslip_file',
       'payment_record_file',
       "payroll_id",
       'gross_pay',
       'tax',
       'employee_ni_deduction',
       'employer_ni',

       'bank_id',
       'sort_code',
       'account_number',
       'account_name',

       "created_by"
    ];


    protected $casts = [
        'payment_record_file' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by',"id");
    }

}
