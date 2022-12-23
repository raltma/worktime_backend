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
        Schema::create('hour_reports', function (Blueprint $table) {
            $table->id();
            $table->date("date_selected");
            $table->integer("shift");
            $table->integer("hours");
            $table->boolean("overtime")->default(false);
            $table->integer("overtime_hours")->default(0);
            $table->boolean("confirmed")->default(false);
            $table->foreignId("user_id")->constrained();
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
        Schema::dropIfExists('hour_reports');
    }
};
