<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public function students()
    {
        $this->hasMany(Student::class);
    }
}
