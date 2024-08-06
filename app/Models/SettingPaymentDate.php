<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPaymentDate extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [
        'payment_type',
        'day_of_week',
        'day_of_month',
        'custom_date',
        'custom_frequency_interval',
        'custom_frequency_unit',

        'is_active',
        'is_default',
        'business_id',
        'created_by',
        'role_specific_settings',
    ];

    protected $casts = [
        'role_specific_settings' => 'array',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
