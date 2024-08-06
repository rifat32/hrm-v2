<?php





namespace App\Models;

use App\Traits\DatabaseUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLetter extends Model
{
    use HasFactory, DatabaseUtil;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnectionForBusiness();
    }
    protected $fillable = [
                    'issue_date',
                    'status',
                    'letter_content',
                    'sign_required',
                    'attachments',
                    'user_id',

        "business_id",
        "created_by"
    ];







    protected $casts = [
        'attachments' => 'array',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
















}

