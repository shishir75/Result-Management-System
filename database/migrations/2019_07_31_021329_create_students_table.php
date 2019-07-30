<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('class_roll');
            $table->string('session');
            $table->unsignedBigInteger('dept_id');
            $table->bigInteger('reg_no');
            $table->bigInteger('exam_roll');
            $table->string('hall');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('students');
    }
}
