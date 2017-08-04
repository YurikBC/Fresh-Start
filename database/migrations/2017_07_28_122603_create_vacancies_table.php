<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('location', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 255)->unique();
            $table->bigInteger('date')->nullable();
            $table->string('employment', 100)->nullable();
            $table->string('company', 225)->nullable();
            $table->point('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
