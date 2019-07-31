<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model
{
    public function dept()
    {
        return $this->belongsTo(Dept::class)->withDefault();
    }

    public function session()
    {
        return $this->belongsTo(Session::class)->withDefault();
    }

    public function course()
    {
        return $this->belongsTo(Course::class)->withDefault();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class)->withDefault();
    }

    public function year()
    {
        return $this->belongsTo(Year::class)->withDefault();
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class)->withDefault();
    }
}
