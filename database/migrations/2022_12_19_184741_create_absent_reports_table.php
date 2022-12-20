<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absent_reports', function (Blueprint $table) {
            $table->id();
            $table->date("date_selected");
            $table->integer("shift");
            $table->integer("hours");
            $table->string("reason");
            $table->string("filepath")->nullable();
            $table->foreignId("user_id")->constrained;
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
        Schema::dropIfExists('absent_reports');
    }
};
