<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }
}
