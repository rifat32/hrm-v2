<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentUser extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [
        'department_id', 'user_id'
    ];
    public function department() {
        return $this->hasOne(Department::class,  'id', 'department_id');
    }
}
