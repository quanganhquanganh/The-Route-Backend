<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_sub', function (Blueprint $table) {
            $table->id('route_sub_id');
            $table->string('name_route_sub');
            $table->integer('route_id');
            $table->date('start_time');
            $table->date('end_time');
            $table->foreign('route_id')->references('route_id')->on('route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_sub');
    }
}
