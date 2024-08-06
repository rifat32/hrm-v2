<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeUserWorkShiftHistory extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    protected $fillable = [
        "work_shift_id",
        "user_id",
        "from_date",
        "to_date"

    ];
    protected $dates = [
        'from_date',
        'to_date',
        // Add other date attributes here if needed
    ];

    public function work_shift_history(){
        return $this->hasOne(WorkShiftHistory::class,'id', 'work_shift_id');
    }
    public function user() {
        return $this->hasOne(User::class,  'id', 'user_id');
    }


}
