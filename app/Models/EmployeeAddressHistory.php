<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddressHistory extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [

        "address_line_1",
        "address_line_2",
        "country",
        "city",
        "postcode",
        "lat",
        "long",
        'user_id',
        "from_date",
        "to_date",
        "is_manual",
        'created_by'
    ];



    public function employee(){
        return $this->hasOne(User::class,'id', 'user_id');
    }





}
