<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceArrear extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, "attendance_id" ,'id');
    }






}
