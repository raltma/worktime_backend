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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('bs_id')->unique;
            $table->string('name');
            $table->string('department');
            $table->timestamps();
        });

         Schema::table('users', function (Blueprint $table) {
            $table->foreign('admin_department')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Schema::table('users', function(Blueprint $table){
            $table->dropForeign(['bs_department_id']);
        }); */
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_admin_department_foreign');
        });
        Schema::dropIfExists('departments');
    }
};
