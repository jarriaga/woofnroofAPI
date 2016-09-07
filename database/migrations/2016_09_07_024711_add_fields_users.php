<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebookId',50)->nullable();
            $table->date('birthday')->nullable();
            $table->double('latitude',10,8)->nullable();
            $table->double('longitude',10,8)->nullable();
            $table->string('mobile',15);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn(['facebookId','birthday','latitude','longitude','mobile']);
        });
    }
}
