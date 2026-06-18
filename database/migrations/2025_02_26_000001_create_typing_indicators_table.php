<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('typing_indicators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->string('typer', 20); // 'admin' or 'user'
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::table('typing_indicators', function (Blueprint $table) {
            $table->unique(['message_id', 'typer']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('typing_indicators');
    }
};
