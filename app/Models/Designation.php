<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
