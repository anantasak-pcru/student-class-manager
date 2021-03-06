<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('std_id', 12)->primary();
            $table->string('f_name', 100)->nullable()->default('');
            $table->string('l_name', 100)->nullable()->default('');
            $table->string('address', 100)->nullable()->default('');
            $table->string('tel', 15)->nullable()->default('');
            $table->string('password', 100)->nullable()->default('');
            $table->unsignedInteger('m_id')->nullable()->default(null);
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
