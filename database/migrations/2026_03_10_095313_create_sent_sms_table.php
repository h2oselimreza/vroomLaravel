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
        Schema::create('sent_sms', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->index();
            $table->string('sms_template', 256);
            $table->integer('sms_count');
            $table->text('custom_sms')->nullable();
            $table->string('channel_type', 100)->nullable();
            $table->string('module_type', 100)->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('created_by', 70);
            $table->string('updated_by', 70);
            $table->dateTime('created_dt_tm');
            $table->dateTime('updated_dt_tm');
            $table->integer('job_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_sms');
    }
};
