<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'profiles';
    protected $fillable = [
        'full_name',
        'phone',
        'birthday',
        'identity_card',
        'gender',
        'address',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
