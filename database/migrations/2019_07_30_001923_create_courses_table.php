<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dept_id');
            $table->integer('year_semester_id');
            $table->string('course_code');
            $table->string('course_title');
            $table->float('credit_hour');
            $table->integer('incourse_marks');
            $table->integer('final_marks');
            $table->boolean('is_lab')->default(0);
            $table->timestamps();
            $table->foreign('dept_id')->references('id')->on('depts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
