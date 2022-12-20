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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('bs_id')->unique; //Id that is in biostar table
            $table->string('fingerprint_id'); //Id that is in biostar table pointing to fingerprint
            $table->string('username')->unique;
            $table->string('name');
            $table->string('taavi_code');
            $table->string('bs_department_id')->nullable(); //department id that is coming from biostar
            $table->string('email');
            $table->boolean('admin')->default(false);
            $table->unsignedBigInteger('admin_department')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->index('admin_department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
