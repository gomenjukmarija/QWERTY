<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{

    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();;
            $table->string('last_name')->nullable();;
            $table->date('bdate')->nullable();;
            $table->integer('sex')->nullable();;
            $table->integer('country')->nullable();;
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
