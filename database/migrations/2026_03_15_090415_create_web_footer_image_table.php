<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_footer_image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image', 256);
            $table->integer('image_order');
            $table->string('created_by', 70);
            $table->dateTime('created_dt_tm');
            $table->string('updated_by', 70)->nullable();
            $table->dateTime('updated_dt_tm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_footer_image');
    }
};
