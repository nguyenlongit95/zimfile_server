<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->integer('group_name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('group_id')->nullable();
        });

        Schema::create('editor_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('editor_id');
            $table->integer('group_id');
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
        //
    }
}
