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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('nit');
            $table->string('ncr');
            $table->string('cuenta_no');
            $table->string('giro');
            $table->string('tipoContribuyente');
            $table->string('tipoEstablecimiento');
            $table->string('logo');
            $table->foreignId('economicactivity_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('phone_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('companies');
    }
};
