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
        Schema::create('dte', function (Blueprint $table) {
            $table->id();
            $table->integer('versionJson');
            $table->foreignId('ambiente_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tipoDte');
            $table->string('tipoModelo');
            $table->string('tipoTransmision');
            $table->string('tipoContingencia');
            $table->string('idContingencia');
            $table->string('nameTable');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('id_doc');
            $table->string('codTransaction');
            $table->string('desTransaction');
            $table->string('type_document');
            $table->string('id_doc_Ref1');
            $table->string('id_doc_Ref2');
            $table->string('type_invalidacion');
            $table->string('codEstado');
            $table->string('Estado');
            $table->string('codigoGeneracion');
            $table->string('selloRecibido');
            $table->dateTime('fhRecibido');
            $table->string('estadoHacienda');
            $table->string('nSends');
            $table->string('codeMessage');
            $table->string('claMessage');
            $table->string('descriptionMessage');
            $table->string('detailsMessage');
            $table->timestamps();
            $table->string('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dte');
    }
};
