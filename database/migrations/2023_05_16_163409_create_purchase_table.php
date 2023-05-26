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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id');
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('number');
            $table->date('date');
            $table->decimal('exenta')->nullable();
            $table->decimal('gravada')->nullable();
            $table->decimal('iva')->nullable();
            $table->decimal('contrns')->nullable();
            $table->decimal('fovial')->nullable();
            $table->decimal('iretenido')->nullable();
            $table->decimal('otros')->nullable();
            $table->decimal('total')->nullable();
            $table->date('fingreso');
            $table->string('periodo');
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
        Schema::dropIfExists('purchases');
    }
};
