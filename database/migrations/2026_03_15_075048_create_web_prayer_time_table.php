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
        Schema::create('web_prayer_time', function (Blueprint $table) {
            $table->increments('id');
            $table->date('prayer_date');
            $table->string('fajr', 15);
            $table->string('zuhor', 15);
            $table->string('asor', 15);
            $table->string('maghrib', 15);
            $table->string('isha', 15);
            $table->string('jumma', 15);
            $table->string('sunrise', 15);
            $table->string('sunset', 15);
            $table->integer('data_source');
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
        Schema::dropIfExists('web_prayer_time');
    }
};
