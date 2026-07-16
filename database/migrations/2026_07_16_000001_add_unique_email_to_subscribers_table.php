<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $subscribers = DB::table('subscribers')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('id')
            ->get(['id', 'email']);

        foreach ($subscribers as $subscriber) {
            $normalized = strtolower(trim($subscriber->email));
            if ($normalized !== $subscriber->email) {
                DB::table('subscribers')
                    ->where('id', $subscriber->id)
                    ->update(['email' => $normalized]);
            }
        }

        $duplicates = DB::table('subscribers')
            ->select('email', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as c'))
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->groupBy('email')
            ->having('c', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('subscribers')
                ->where('email', $duplicate->email)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('subscribers', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};
