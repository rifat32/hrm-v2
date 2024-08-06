<?php

namespace App\Models;

use App\Traits\DatabaseUtil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNoteMention extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [
        'user_note_id',
        'user_id',
    ];

    // Relationships
    public function user_note()
    {
        return $this->belongsTo(UserNote::class);
    }

    public function mentioned_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
