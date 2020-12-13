<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'fk_user_id', 'fk_food_id', 'quantity', 'total', 'status', 'payment_url'
    ];

    public function food()
    {
        return $this->hasOne(Food::class, 'id', 'fk_food_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'fk_user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
