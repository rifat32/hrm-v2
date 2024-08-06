<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHoliday extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    protected $fillable = [
        'holiday_id', 'user_id'
    ];

    public function holiday() {
        return $this->belongsTo(Holiday::class, "holiday_id","id");
    }

}
