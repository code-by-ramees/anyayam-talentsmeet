<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\Result;
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
        // 1. Settings
        Setting::set('fest_name', "SNEC Talents' Meet 2026-27");
        Setting::set('college_name', "Shamsul Ulama Islamic & Arts College");
        Setting::set('edition', "Edition 03");
        Setting::set('allow_group_member_add', '1');
        Setting::set('tie_breaker', 'most_first_prizes');

        // 2. Super Admin User
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator',
                'email' => 'admin@collegefest.com',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        // 3. Sections
        $bakalooriyya = Section::firstOrCreate(
            ['code' => 'BAK'],
            [
                'name' => 'Bakalooriyya',
                'description' => 'First major competition section for senior degree students.',
                'status' => true,
            ]
        );

        $sanaviyya = Section::firstOrCreate(
            ['code' => 'SAN'],
            [
                'name' => 'Sanaviyya Ulya',
                'description' => 'Second major competition section for intermediate college students.',
                'status' => true,
            ]
        );

        // 4. Categories & Marks Configuration
        $catA = Category::firstOrCreate(
            ['code' => 'CAT-A'],
            [
                'name' => 'Category A (General Stage)',
                'description' => 'General stage items including Elocution, Debates & Versification',
                'first_marks' => 10,
                'second_marks' => 7,
                'third_marks' => 5,
            ]
        );

        $catB = Category::firstOrCreate(
            ['code' => 'CAT-B'],
            [
                'name' => 'Category B (Literary & Writing)',
                'description' => 'Off-stage writing and literature events including Essays and Poetry',
                'first_marks' => 15,
                'second_marks' => 10,
                'third_marks' => 5,
            ]
        );

        $catC = Category::firstOrCreate(
            ['code' => 'CAT-C'],
            [
                'name' => 'Category C (Arts & Designing)',
                'description' => 'Calligraphy, Pencil Drawing, and Poster Design',
                'first_marks' => 12,
                'second_marks' => 8,
                'third_marks' => 4,
            ]
        );

        $catD = Category::firstOrCreate(
            ['code' => 'CAT-D'],
            [
                'name' => 'Category D (Group Choral & Chants)',
                'description' => 'Group Nasheeda, Qawwali, and Choral Renditions',
                'first_marks' => 20,
                'second_marks' => 15,
                'third_marks' => 10,
            ]
        );

        // 5. Participating Groups & Group User Logins
        $groupsData = [
            [
                'section_id' => $bakalooriyya->id,
                'name' => 'Emerald Hawks',
                'code' => 'BAK-EMR',
                'username' => 'emerald',
                'password' => 'group123',
                'color' => '#10b981',
                'description' => 'Bakalooriyya Section - Emerald House',
            ],
            [
                'section_id' => $bakalooriyya->id,
                'name' => 'Ruby Knights',
                'code' => 'BAK-RUB',
                'username' => 'ruby',
                'password' => 'group123',
                'color' => '#ef4444',
                'description' => 'Bakalooriyya Section - Ruby House',
            ],
            [
                'section_id' => $sanaviyya->id,
                'name' => 'Sapphire Tigers',
                'code' => 'SAN-SAP',
                'username' => 'sapphire',
                'password' => 'group123',
                'color' => '#3b82f6',
                'description' => 'Sanaviyya Ulya Section - Sapphire House',
            ],
            [
                'section_id' => $sanaviyya->id,
                'name' => 'Topaz Warriors',
                'code' => 'SAN-TOP',
                'username' => 'topaz',
                'password' => 'group123',
                'color' => '#f59e0b',
                'description' => 'Sanaviyya Ulya Section - Topaz House',
            ],
        ];

        $groups = [];
        foreach ($groupsData as $data) {
            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'] . ' Leader',
                    'email' => $data['username'] . '@fest.com',
                    'password' => Hash::make($data['password']),
                    'role' => 'group_user',
                ]
            );

            $group = Group::updateOrCreate(
                ['code' => $data['code']],
                [
                    'section_id' => $data['section_id'],
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'color' => $data['color'],
                    'allow_member_registration' => true,
                    'status' => 'active',
                ]
            );

            $user->update(['group_id' => $group->id]);
            $groups[$data['code']] = $group;

            // Seed sample members for each group
            for ($i = 1; $i <= 4; $i++) {
                Member::firstOrCreate(
                    ['registration_no' => $data['code'] . '-M0' . $i],
                    [
                        'group_id' => $group->id,
                        'name' => 'Participant ' . $data['name'] . ' ' . $i,
                        'class_year' => ($data['section_id'] === $bakalooriyya->id) ? 'Degree Year ' . (($i % 3) + 1) : 'Sanaviyya Year ' . (($i % 2) + 1),
                        'phone' => '987654321' . $i,
                        'gender' => ($i % 2 === 0) ? 'Male' : 'Female',
                    ]
                );
            }
        }

        // 6. Programs
        $programsData = [
            // Bakalooriyya Programs
            [
                'section_id' => $bakalooriyya->id,
                'category_id' => $catA->id,
                'name' => 'English Elocution',
                'code' => 'BAK-ELO-ENG',
                'type' => 'individual',
                'venue' => 'Main Auditorium',
                'status' => 'completed',
            ],
            [
                'section_id' => $bakalooriyya->id,
                'category_id' => $catB->id,
                'name' => 'Arabic Essay Writing',
                'code' => 'BAK-ESS-ARB',
                'type' => 'individual',
                'venue' => 'Seminar Hall A',
                'status' => 'completed',
            ],
            [
                'section_id' => $bakalooriyya->id,
                'category_id' => $catD->id,
                'name' => 'Choral Nasheeda',
                'code' => 'BAK-NASH-GRP',
                'type' => 'group',
                'max_participants' => 7,
                'venue' => 'Open Stage',
                'status' => 'completed',
            ],
            // Sanaviyya Ulya Programs
            [
                'section_id' => $sanaviyya->id,
                'category_id' => $catA->id,
                'name' => 'Urdu Speech',
                'code' => 'SAN-SPH-URD',
                'type' => 'individual',
                'venue' => 'Main Auditorium',
                'status' => 'completed',
            ],
            [
                'section_id' => $sanaviyya->id,
                'category_id' => $catC->id,
                'name' => 'Islamic Calligraphy',
                'code' => 'SAN-CAL-ART',
                'type' => 'individual',
                'venue' => 'Arts Room 2',
                'status' => 'completed',
            ],
            [
                'section_id' => $sanaviyya->id,
                'category_id' => $catD->id,
                'name' => 'Group Duff Song',
                'code' => 'SAN-DUFF-GRP',
                'type' => 'group',
                'max_participants' => 6,
                'venue' => 'Open Stage',
                'status' => 'ongoing',
            ],
        ];

        $programs = [];
        foreach ($programsData as $pData) {
            $programs[$pData['code']] = Program::firstOrCreate(
                ['code' => $pData['code']],
                $pData
            );
        }

        // 7. Seed Sample Results (Published and Draft)
        // Program 1: English Elocution (Cat A: 10, 7, 5)
        $p1 = $programs['BAK-ELO-ENG'];
        Result::firstOrCreate(
            ['program_id' => $p1->id, 'position' => 'first'],
            [
                'group_id' => $groups['BAK-EMR']->id,
                'participant_name' => 'Muhammad Bilal (BAK-EMR)',
                'marks' => $catA->first_marks,
                'remarks' => 'Outstanding fluency and articulation',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
        Result::firstOrCreate(
            ['program_id' => $p1->id, 'position' => 'second'],
            [
                'group_id' => $groups['BAK-RUB']->id,
                'participant_name' => 'Ahmad Fazil (BAK-RUB)',
                'marks' => $catA->second_marks,
                'remarks' => 'Good presentation',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // Program 2: Arabic Essay (Cat B: 15, 10, 5)
        $p2 = $programs['BAK-ESS-ARB'];
        Result::firstOrCreate(
            ['program_id' => $p2->id, 'position' => 'first'],
            [
                'group_id' => $groups['BAK-RUB']->id,
                'participant_name' => 'Zayd Rahman (BAK-RUB)',
                'marks' => $catB->first_marks,
                'remarks' => 'Exceptional classical vocabulary',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
        Result::firstOrCreate(
            ['program_id' => $p2->id, 'position' => 'second'],
            [
                'group_id' => $groups['BAK-EMR']->id,
                'participant_name' => 'Tariq Anwar (BAK-EMR)',
                'marks' => $catB->second_marks,
                'remarks' => 'Well structured essay',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // Program 3: Choral Nasheeda (Cat D: 20, 15, 10)
        $p3 = $programs['BAK-NASH-GRP'];
        Result::firstOrCreate(
            ['program_id' => $p3->id, 'position' => 'first'],
            [
                'group_id' => $groups['BAK-EMR']->id,
                'participant_name' => 'Emerald Hawks Choral Team',
                'marks' => $catD->first_marks,
                'remarks' => 'Flawless harmony and vocal sync',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
        Result::firstOrCreate(
            ['program_id' => $p3->id, 'position' => 'second'],
            [
                'group_id' => $groups['BAK-RUB']->id,
                'participant_name' => 'Ruby Knights Vocal Squad',
                'marks' => $catD->second_marks,
                'remarks' => 'Melodious chorus',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // Sanaviyya Program: Urdu Speech (Cat A: 10, 7, 5)
        $p4 = $programs['SAN-SPH-URD'];
        Result::firstOrCreate(
            ['program_id' => $p4->id, 'position' => 'first'],
            [
                'group_id' => $groups['SAN-SAP']->id,
                'participant_name' => 'Hamza Ali (SAN-SAP)',
                'marks' => $catA->first_marks,
                'remarks' => 'Passionate delivery',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
        Result::firstOrCreate(
            ['program_id' => $p4->id, 'position' => 'second'],
            [
                'group_id' => $groups['SAN-TOP']->id,
                'participant_name' => 'Ibrahim Khalil (SAN-TOP)',
                'marks' => $catA->second_marks,
                'remarks' => 'Good rhetoric',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
