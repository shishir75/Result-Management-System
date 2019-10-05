<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalMarks extends Model
{
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
