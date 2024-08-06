<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [
        'name', 'description', 'start_date', 'end_date', 'repeats_annually',  'is_active', 'business_id', "status", "created_by",
    ];

    public function departments() {
        return $this->belongsToMany(Department::class, 'department_holidays', 'holiday_id', 'department_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_holidays', 'holiday_id', 'user_id');
    }


    public function creator() {
        return $this->belongsTo(User::class, "created_by","id");
    }

    public function payroll_holiday()
    {
        return $this->hasOne(PayrollHoliday::class, "holiday_id" ,'id');
    }




    // public function getCreatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }
    // public function getUpdatedAtAttribute($value)
    // {
    //     return (new Carbon($value))->format('d-m-Y');
    // }




    public function getStartDateAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }
    public function getEndDateAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }






}
