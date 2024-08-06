<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollLeaveRecord extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    protected $fillable = [
        'payroll_id',
        'leave_record_id',
        // 'date',
        // 'start_time',
        // 'end_time',
        // "leave_hours",
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    public function leave_record()
    {
        return $this->belongsTo(LeaveRecord::class, 'leave_record_id');
    }









}
