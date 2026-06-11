<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_username')->index();
            $table->string('sender_username')->nullable()->index();
            $table->string('type')->index();
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedBigInteger('plan_estudio_id')->nullable()->index();
            $table->timestamp('read_at')->nullable()->index();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('plan_estudio_id')
                ->references('id')
                ->on('plan-estudio')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_notifications');
    }
};
