<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('members') && !Schema::hasColumn('members', 'chest_number')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('chest_number')->nullable()->after('uid');
            });
        }

        if (Schema::hasTable('program_registrations') && !Schema::hasColumn('program_registrations', 'chest_number')) {
            Schema::table('program_registrations', function (Blueprint $table) {
                $table->string('chest_number')->nullable()->after('member_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'chest_number')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropColumn('chest_number');
            });
        }

        if (Schema::hasTable('program_registrations') && Schema::hasColumn('program_registrations', 'chest_number')) {
            Schema::table('program_registrations', function (Blueprint $table) {
                $table->dropColumn('chest_number');
            });
        }
    }
};
