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
        if (!Schema::hasTable('program_registrations')) {
            Schema::create('program_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                $table->string('registered_by')->default('group'); // 'admin' or 'group'
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['program_id', 'member_id'], 'unique_program_member');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_registrations');
    }
};
