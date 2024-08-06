<?php

namespace App\Models;

use App\Http\Utils\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabledRecruitmentProcess extends Model
{
    use HasFactory, DatabaseUtil;



    protected static function booted()
    {
        static::addGlobalScope('connection', function ($builder) {
            $connection = 'mysql'; // Default connection

            if (!empty(auth()->user()->business_id)) {
                $businessId = auth()->user()->business_id;
                $connection = 'business_' . $businessId;
            }

            $builder->getConnection()->setConnection($connection);
        });
    }


    protected $fillable = [
        'recruitment_process_id',
        'business_id',
        'created_by',
        // Add other fillable columns if needed
    ];


}
