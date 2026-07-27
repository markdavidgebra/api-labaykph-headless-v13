<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('type', 32)->default('general')->after('phone');
        });

        DB::table('inquiries')
            ->where('message', 'like', 'VIP Travel Request%')
            ->update(['type' => 'vip']);
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
