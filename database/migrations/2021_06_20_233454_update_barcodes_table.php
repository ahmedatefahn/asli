<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barcodes', function (Blueprint $table) {
           $table->renameColumn('code', 'secret_code');
           $table->text('public_code');
           $table->date('custom_creation_date')->nullable();
           $table->date('scan_date')->nullable();
           $table->unsignedBigInteger('scanned_by')->nullable(); // reflects customer_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
