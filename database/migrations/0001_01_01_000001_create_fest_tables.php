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
        // 1. Sections: Bakalooriyya & Sanaviyya Ulya
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bakalooriyya, Sanaviyya Ulya
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Groups (Houses/Teams across all sections)
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#9b111e');
            $table->boolean('allow_member_registration')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 3. Members (Belong to a Group and a Section like Junior/Sanaviyya or Senior/Bakalooriyya)
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('name');
            $table->string('uid')->unique();
            $table->string('chest_number')->nullable();
            $table->string('class_year');
            $table->timestamps();
        });

        // 4. Categories & configurable position marks
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Category A, B, C, D
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('first_marks')->default(10);
            $table->integer('second_marks')->default(7);
            $table->integer('third_marks')->default(5);
            $table->timestamps();
        });

        // 5. Programs / Competitions
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('stage_type', ['stage', 'non_stage'])->default('stage');
            $table->enum('type', ['individual', 'group'])->default('individual');
            $table->integer('max_participants')->default(1);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        // 6. Program Registrations
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('chest_number')->nullable();
            $table->string('registered_by')->default('group');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'member_id'], 'unique_program_member');
        });

        // 7. Results
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('participant_name');
            $table->enum('position', ['first', 'second', 'third']);
            $table->integer('marks');
            $table->string('remarks')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 7. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 8. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('results');
        Schema::dropIfExists('program_registrations');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('members');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('sections');
    }
};
