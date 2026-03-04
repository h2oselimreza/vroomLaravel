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
        Schema::create('web_gallery_image', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('gallery_album');
            $table->string('image', 256);
            $table->integer('is_active')->default(1);
            $table->integer('home_flag')->default(0);
            $table->string('created_by', 70);
            $table->string('updated_by', 70);
            $table->dateTime('created_dt_tm');
            $table->dateTime('updated_dt_tm');
            $table->foreign('gallery_album')
                ->references('id')
                ->on('web_gallery_album')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_gallery_image');
    }
};
