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
        Schema::create('piece_reports', function (Blueprint $table) {
            $table->id();
            $table->date("date_selected");
            $table->integer("shift");
            $table->string("workplace");
            $table->boolean("confirmed")->default(false);
            $table->foreignId("user_id")->constrained;
            $table->unsignedBigInteger('confirmer_id')->nullable();
            $table->string("biostar_check")->default("Pole tehtud");
            $table->timestamp("confirmed_at")->nullable();
            $table->foreign("confirmer_id")
                ->references("id")
                ->on("users");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piece_reports');
    }
};
