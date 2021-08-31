<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('director_id');
            $table->integer('file_id')->nullable();
            $table->string('file_jobs', 255)->nullable();   // File request submitted by customer
            $table->integer('status')->default(1);           // 0: reject, 1 chưa assign, 2 đã asign, 3 confirm, 4 done
            $table->timestamp('time_upload')->nullable();
            $table->timestamp('time_confirm')->nullable();
            $table->timestamp('time_done')->nullable();
            $table->integer('type')->nullable();                    // 1: Photo editing	2: Day to dusk	3: Virtual Staging	4:Additional Retouching
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
