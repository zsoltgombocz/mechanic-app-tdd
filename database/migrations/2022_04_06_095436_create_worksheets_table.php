<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worksheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->timestamps();
            $table->string('customer_name');
            $table->string('customer_addr');
            $table->string('vehicle_license');
            $table->string('vehicle_brand');
            $table->string('vehicle_model');
            $table->unsignedBigInteger('mechanic_id');
            $table->tinyInteger('closed');
            $table->dateTime('closed_at')->nullable()->default(NULL);
            $table->tinyInteger('payment');

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worksheets');
    }
}
