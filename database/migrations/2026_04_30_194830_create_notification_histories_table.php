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
        Schema::create('notification_histories', function (Blueprint $table) {
            $table->id();
            $table->string('recipient');
            $table->string('channel');
            $table->text('message');
            $table->enum('status', ['success', 'failed']);
            $table->text('error')->nullable();
            $table->timestamp('sent_at');

            $table->index(['channel', 'status']);
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_histories');
    }
};
