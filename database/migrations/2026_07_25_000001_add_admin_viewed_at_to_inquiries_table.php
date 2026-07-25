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
            if (! Schema::hasColumn('inquiries', 'admin_viewed_at')) {
                $table->timestamp('admin_viewed_at')->nullable()->after('updated_at');
            }
        });

        // Existing rows are treated as already seen so only new inquiries get a badge.
        DB::table('inquiries')->whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'admin_viewed_at')) {
                $table->dropColumn('admin_viewed_at');
            }
        });
    }
};
