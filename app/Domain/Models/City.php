<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'cities';
    protected $fillable = [
        'name',
        'address'
    ];
    public function cinemas()
    {
        return $this->hasMany(Cinema::class, 'city_id', 'id');
    }
}
