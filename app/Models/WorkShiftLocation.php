<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShiftLocation extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
}
