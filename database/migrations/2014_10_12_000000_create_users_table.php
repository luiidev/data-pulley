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
            $table->string('nick', 50)->unique();
            $table->string('password');
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('dni', 10)->nullable();
            $table->string('email', 100)->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('role')->default(3);
            $table->tinyInteger('state')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
