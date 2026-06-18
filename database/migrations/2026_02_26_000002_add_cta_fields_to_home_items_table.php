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
        Schema::table('home_items', function (Blueprint $table) {
            $table->string('cta_label')->nullable()->after('blog_status');
            $table->string('cta_title')->nullable()->after('cta_label');
            $table->text('cta_text')->nullable()->after('cta_title');
            $table->string('cta_background')->nullable()->after('cta_text');
            $table->string('cta_status', 10)->nullable()->default('Show')->after('cta_background');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_items', function (Blueprint $table) {
            $table->dropColumn(['cta_label', 'cta_title', 'cta_text', 'cta_background', 'cta_status']);
        });
    }
};
