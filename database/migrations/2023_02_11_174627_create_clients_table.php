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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('secondname');
            $table->string('email');
            $table->string('ncr')->nullable();
            $table->string('giro')->nullable();
            $table->string('nit')->nullable();
            $table->string('legal');
            $table->string('tpersona');
            $table->date('birthday');
            $table->string('empresa')->nullable();
            $table->string('contribuyente');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('economicactivity_id')->nullable()->default(0)->constrained()->onDelete('cascade');
            $table->foreignId('phone_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
};
