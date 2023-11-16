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
            $table->string('tipoContingencia')->nullable();
            $table->string('idContingencia')->nullable();
            $table->string('nameTable');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('id_doc');
            $table->string('codTransaction');
            $table->string('desTransaction');
            $table->string('type_document');
            $table->string('id_doc_Ref1')->nullable();
            $table->string('id_doc_Ref2')->nullable();
            $table->string('type_invalidacion')->nullable();
            $table->string('codEstado')->nullable();
            $table->string('Estado')->nullable();
            $table->string('codigoGeneracion')->nullable();
            $table->string('selloRecibido')->nullable();
            $table->dateTime('fhRecibido')->nullable();
            $table->string('json')->nullable();
            $table->string('nSends')->nullable();
            $table->string('codeMessage')->nullable();
            $table->string('claMessage')->nullable();
            $table->string('descriptionMessage')->nullable();
            $table->string('detailsMessage')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
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
