<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearHeadApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_head_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('dept_id');
            $table->unsignedBigInteger('course_id');
            $table->boolean('approved')->default(0);
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
        Schema::dropIfExists('year_head_approvals');
    }
}
