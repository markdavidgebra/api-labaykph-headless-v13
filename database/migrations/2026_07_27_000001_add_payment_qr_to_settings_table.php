<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'payment_qr')) {
                $table->string('payment_qr')->nullable()->after('banner');
            }
            if (! Schema::hasColumn('settings', 'payment_qr_note')) {
                $table->string('payment_qr_note')->nullable()->after('payment_qr');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'payment_qr_note')) {
                $table->dropColumn('payment_qr_note');
            }
            if (Schema::hasColumn('settings', 'payment_qr')) {
                $table->dropColumn('payment_qr');
            }
        });
    }
};
