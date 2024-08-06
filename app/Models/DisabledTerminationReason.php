<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabledTerminationReason extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    protected $fillable = [
        'termination_reason_id',
        'business_id',
        'created_by',
        // Add other fillable columns if needed
    ];

}
