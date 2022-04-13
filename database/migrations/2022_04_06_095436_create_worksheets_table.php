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
            $table->unsignedBigInteger('admin_id')->nullable()->default(NULL);
            $table->timestamps();
            $table->string('customer_name')->nullable()->default(NULL);
            $table->string('customer_addr')->nullable()->default(NULL);
            $table->string('vehicle_license')->nullable()->default(NULL);
            $table->string('vehicle_brand')->nullable()->default(NULL);
            $table->string('vehicle_model')->nullable()->default(NULL);
            $table->unsignedBigInteger('mechanic_id')->nullable()->default(NULL);
            $table->tinyInteger('closed')->default('0');
            $table->dateTime('closed_at')->nullable()->default(NULL);
            $table->tinyInteger('payment')->default(-1);

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('set null');
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
