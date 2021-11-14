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
            $table->id();
            $table->string('name_route_sub');
            $table->date('start_time');
            $table->date('end_time');
            $table->foreignId('route_id')->constrained('route');
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
