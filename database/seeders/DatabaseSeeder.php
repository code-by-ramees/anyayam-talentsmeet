<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Section;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. System Settings
        Setting::set('fest_name', "Masa Talents' Meet 26");
        Setting::set('college_name', "Shamsul Ulama Islamic & Arts College (SNEC)");
        Setting::set('college_place', "Pathiyankara, Alappuzha");
        Setting::set('edition', "Meet '26");
        Setting::set('tagline', "“കല കലഹിക്കട്ടെ; സത്യം സംസാരിക്കട്ടെ”");
        Setting::set('theme_title', "JUSTICE — അന്യായം");
        Setting::set('allow_group_member_add', '1');
        Setting::set('allow_event_registration', '1');
        Setting::set('tie_breaker', 'most_first_prizes');

        // 2. Super Admin User
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator',
                'email' => 'admin@collegefest.com',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        // 3. Sections
        Section::firstOrCreate(
            ['code' => 'BAK'],
            [
                'name' => 'Bakalooriyya',
                'description' => 'First major competition section for senior degree students.',
                'status' => true,
            ]
        );

        Section::firstOrCreate(
            ['code' => 'SAN'],
            [
                'name' => 'Sanaviyya Ulya',
                'description' => 'Second major competition section for intermediate college students.',
                'status' => true,
            ]
        );

        Section::firstOrCreate(
            ['code' => 'GEN'],
            [
                'name' => 'General',
                'description' => 'Open section — all groups from every section can compete in these programs.',
                'status' => true,
            ]
        );

        // 4. Categories & Marks Configuration
        Category::firstOrCreate(
            ['code' => 'CAT-A'],
            [
                'name' => 'Category A (General Stage)',
                'description' => 'General stage items including Elocution, Debates & Versification',
                'first_marks' => 10,
                'second_marks' => 7,
                'third_marks' => 5,
            ]
        );

        Category::firstOrCreate(
            ['code' => 'CAT-B'],
            [
                'name' => 'Category B (Literary & Writing)',
                'description' => 'Off-stage writing and literature events including Essays and Poetry',
                'first_marks' => 15,
                'second_marks' => 10,
                'third_marks' => 5,
            ]
        );

        Category::firstOrCreate(
            ['code' => 'CAT-C'],
            [
                'name' => 'Category C (Arts & Designing)',
                'description' => 'Calligraphy, Pencil Drawing, and Poster Design',
                'first_marks' => 12,
                'second_marks' => 8,
                'third_marks' => 4,
            ]
        );

        Category::firstOrCreate(
            ['code' => 'CAT-D'],
            [
                'name' => 'Category D (Group Choral & Chants)',
                'description' => 'Group Nasheeda, Qawwali, and Choral Renditions',
                'first_marks' => 20,
                'second_marks' => 15,
                'third_marks' => 10,
            ]
        );
    }
}

