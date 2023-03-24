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
        Schema::create('typedocuments', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('type');
            $table->string('description');
            $table->string('codemh');
            $table->string('versionjson')->nullable();
            $table->string('versionjsoncontingencia')->nullable();
            $table->string('contingencia')->nullable();
            $table->string('ambiente')->nullable();
            $table->string('invalidation')->nullable();
            $table->string('periodinvalidation')->nullable();
            $table->string('versionjsoncontingenciainvalidation')->nullable();
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
        Schema::dropIfExists('typedocuments');
    }
};
