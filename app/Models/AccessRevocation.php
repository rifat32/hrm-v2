<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRevocation extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    
    protected $fillable = [
        'user_id',
        'email_access_revoked',
        'system_access_revoked_date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
