<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        "building_name",
        "building_code"
    ];
    public function rooms(){
        return $this->hasMany(Room::class);
    }
    public function courses(){
        return $this->hasMany(Course::class);
    }
}
