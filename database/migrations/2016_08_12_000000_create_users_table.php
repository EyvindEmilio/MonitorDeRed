<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image');
            $table->char('status', 1)->default('Y');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('user_type')->unsigned()->index();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_type')->references('id')->on('users_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
