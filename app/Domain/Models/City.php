<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
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
