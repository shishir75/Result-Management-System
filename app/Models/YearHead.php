<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearHead extends Model
{
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
