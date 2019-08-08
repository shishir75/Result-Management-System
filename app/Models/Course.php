<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function semester()
    {
        return $this->belongsTo('App\Models\Semester', 'year_semester_id');
    }

    public function year()
    {
        return $this->belongsTo('App\Models\Year', 'year_semester_id');
    }

    public function dept()
    {
        return $this->belongsTo(Dept::class);
    }
}
