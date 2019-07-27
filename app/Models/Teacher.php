<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }
}
