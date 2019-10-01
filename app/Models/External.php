<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class External extends Model
{
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }




}
