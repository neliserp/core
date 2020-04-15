<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('username')->unique();
            $table->string('password')->default('*');
            $table->boolean('is_active')->default(true);
            $table->string('email')->nullable()->default('');
            $table->string('firstname')->nullable()->default('');
            $table->string('lastname')->nullable()->default('');
            $table->string('position')->nullable()->default('');
            $table->string('mobile')->nullable()->default('');
            $table->string('code')->nullable()->default('');
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
}
