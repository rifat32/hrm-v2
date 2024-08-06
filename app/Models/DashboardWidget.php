<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }

    protected $fillable = ['widget_name', 'widget_order', 'user_id'];

    // Define the relationship with the 'payruns' table
    public function user()
    {
        return $this->belongsTo(User::class);
    }



}
