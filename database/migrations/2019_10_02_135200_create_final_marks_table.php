<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('dept_id');
            $table->unsignedBigInteger('course_id');
            $table->integer('reg_no');
            $table->integer('exam_roll');
            $table->float('teacher_1_marks')->nullable();
            $table->float('teacher_2_marks')->nullable();
            $table->float('teacher_3_marks')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('dept_id')->references('id')->on('depts')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('final_marks');
    }
}
