<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Group;
use App\Models\Member;
use App\Models\Program;
use App\Models\Result;
use App\Models\Section;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollegeFestTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $bakalooriyya;
    protected $sanaviyya;
    protected $catA;
    protected $emeraldGroup;
    protected $emeraldUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Super Admin
        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'username' => 'admin',
            'email' => 'admin@fest.com',
            'password' => bcrypt('admin123'),
            'role' => 'super_admin',
        ]);

        // 2. Create Sections
        $this->bakalooriyya = Section::create([
            'name' => 'Bakalooriyya',
            'code' => 'BAK',
            'description' => 'Degree level section',
            'status' => true,
        ]);

        $this->sanaviyya = Section::create([
            'name' => 'Sanaviyya Ulya',
            'code' => 'SAN',
            'description' => 'Senior secondary section',
            'status' => true,
        ]);

        // 3. Create Category
        $this->catA = Category::create([
            'name' => 'Category A',
            'code' => 'CAT-A',
            'description' => 'Premier Arts Category',
            'first_marks' => 10,
            'second_marks' => 7,
            'third_marks' => 5,
        ]);

        // 4. Create Group & Group User
        $this->emeraldUser = User::create([
            'name' => 'Emerald Hawks Leader',
            'username' => 'emerald',
            'email' => 'emerald@fest.com',
            'password' => bcrypt('group123'),
            'role' => 'group_user',
        ]);

        $this->emeraldGroup = Group::create([
            'name' => 'Emerald Hawks',
            'code' => 'EMERALD',
            'user_id' => $this->emeraldUser->id,
            'color' => '#10b981',
            'description' => 'Courage and Vision',
            'allow_member_registration' => true,
            'status' => 'active',
        ]);

        $this->emeraldUser->update(['group_id' => $this->emeraldGroup->id]);

        // Seed initial settings
        Setting::set('fest_name', "SNEC Talents' Meet 2026-27");
        Setting::set('allow_group_member_add', '1');
    }

    public function test_public_pages_load_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee("SNEC Talents' Meet");

        $response = $this->get('/results');
        $response->assertStatus(200);

        $response = $this->get('/rankings');
        $response->assertStatus(200);

        $response = $this->get('/programs');
        $response->assertStatus(200);
    }

    public function test_super_admin_can_login_and_access_dashboard()
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->superAdmin);

        $dash = $this->actingAs($this->superAdmin)->get('/admin/dashboard');
        $dash->assertStatus(200);
        $dash->assertSee('Admin Dashboard');
    }

    public function test_group_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->emeraldUser)->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_group_user_can_login_and_access_group_portal()
    {
        $response = $this->post('/group/login', [
            'username' => 'emerald',
            'password' => 'group123',
        ]);

        $response->assertRedirect('/group/dashboard');
        $this->assertAuthenticatedAs($this->emeraldUser);

        $dash = $this->actingAs($this->emeraldUser)->get('/group/dashboard');
        $dash->assertStatus(200);
        $dash->assertSee('Emerald Hawks');
    }

    public function test_admin_can_create_section()
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/sections', [
            'name' => 'Kulliyya',
            'code' => 'KUL',
            'description' => 'Post graduate competition section',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sections', ['code' => 'KUL']);
    }

    public function test_admin_can_create_group_with_login()
    {
        $response = $this->actingAs($this->superAdmin)->post('/admin/groups', [
            'name' => 'Ruby Knights',
            'code' => 'RUBY',
            'username' => 'ruby',
            'password' => 'secret123',
            'color' => '#ef4444',
            'status' => 'active',
            'allow_member_registration' => 1,
        ]);

        $response->assertRedirect('/admin/groups');
        $this->assertDatabaseHas('groups', ['code' => 'RUBY']);
        $this->assertDatabaseHas('users', ['username' => 'ruby', 'role' => 'group_user']);
    }

    public function test_result_entry_and_automatic_mark_calculation()
    {
        $program = Program::create([
            'name' => 'Classical Music Solo',
            'code' => 'CMS-101',
            'section_id' => $this->bakalooriyya->id,
            'category_id' => $this->catA->id,
            'type' => 'individual',
            'status' => 'ongoing',
        ]);

        // Submit First Prize without manual marks (system must auto-fetch 10 from Category A)
        $response = $this->actingAs($this->superAdmin)->post('/admin/results', [
            'program_id' => $program->id,
            'group_id' => $this->emeraldGroup->id,
            'participant_name' => 'Zayd Rahman',
            'position' => 'first',
            'is_published' => 1,
        ]);

        $response->assertRedirect('/admin/results');
        $this->assertDatabaseHas('results', [
            'program_id' => $program->id,
            'group_id' => $this->emeraldGroup->id,
            'position' => 'first',
            'marks' => 10,
            'is_published' => true,
        ]);

        // Verify group's total points updated
        $this->assertEquals(10, $this->emeraldGroup->fresh()->total_marks);
    }

    public function test_duplicate_position_in_same_program_is_rejected()
    {
        $program = Program::create([
            'name' => 'English Elocution',
            'code' => 'EE-102',
            'section_id' => $this->bakalooriyya->id,
            'category_id' => $this->catA->id,
            'type' => 'individual',
            'status' => 'scheduled',
        ]);

        Result::create([
            'program_id' => $program->id,
            'group_id' => $this->emeraldGroup->id,
            'participant_name' => 'First Contestant',
            'position' => 'first',
            'marks' => 10,
            'is_published' => true,
        ]);

        // Attempt to enter first prize again for same program
        $response = $this->actingAs($this->superAdmin)->post('/admin/results', [
            'program_id' => $program->id,
            'group_id' => $this->emeraldGroup->id,
            'participant_name' => 'Duplicate Contestant',
            'position' => 'first',
            'marks' => 10,
            'is_published' => 1,
        ]);

        $response->assertSessionHasErrors('position');
    }

    public function test_draft_results_do_not_count_in_published_scores()
    {
        $program = Program::create([
            'name' => 'Mime',
            'code' => 'MIM-103',
            'section_id' => $this->bakalooriyya->id,
            'category_id' => $this->catA->id,
            'type' => 'group',
            'status' => 'scheduled',
        ]);

        // Save as draft (is_published = 0)
        Result::create([
            'program_id' => $program->id,
            'group_id' => $this->emeraldGroup->id,
            'participant_name' => 'Team Emerald',
            'position' => 'first',
            'marks' => 10,
            'is_published' => false,
        ]);

        // Published marks must remain 0
        $this->assertEquals(0, $this->emeraldGroup->fresh()->total_marks);

        // Group portal results must be empty
        $portalResponse = $this->actingAs($this->emeraldUser)->get('/group/results');
        $portalResponse->assertStatus(200);
        $portalResponse->assertDontSee('Team Emerald');

        // Publish all drafts
        $this->actingAs($this->superAdmin)->post('/admin/results/publish-all');
        $this->assertEquals(10, $this->emeraldGroup->fresh()->total_marks);

        $portalResponse2 = $this->actingAs($this->emeraldUser)->get('/group/results');
        $portalResponse2->assertSee('Team Emerald');
    }

    public function test_group_can_add_member_when_permitted()
    {
        $response = $this->actingAs($this->emeraldUser)->post('/group/members', [
            'name' => 'Bilal Ahmed',
            'section_id' => $this->bakalooriyya->id,
            'uid' => 'UID-9988',
            'class_year' => 'D3',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('members', [
            'group_id' => $this->emeraldGroup->id,
            'section_id' => $this->bakalooriyya->id,
            'uid' => 'UID-9988',
        ]);
    }

    public function test_group_cannot_delete_another_groups_member()
    {
        $otherGroup = Group::create([
            'name' => 'Sapphire Tigers',
            'code' => 'SAPPHIRE',
            'user_id' => null,
            'status' => 'active',
        ]);

        $otherMember = Member::create([
            'group_id' => $otherGroup->id,
            'section_id' => $this->sanaviyya->id,
            'name' => 'Foreign Student',
            'uid' => 'FS-001',
            'class_year' => 'H1',
        ]);

        $response = $this->actingAs($this->emeraldUser)->delete("/group/members/{$otherMember->id}");
        $response->assertStatus(403);
    }

    public function test_admin_can_export_members_csv()
    {
        Member::create([
            'group_id' => $this->emeraldGroup->id,
            'section_id' => $this->bakalooriyya->id,
            'name' => 'Export Student',
            'uid' => 'EXP-101',
            'class_year' => 'D2',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('admin.members.export'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_view_printable_scoresheet()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.rankings.print'));
        $response->assertStatus(200);
        $response->assertSee('Official Group Standings');
    }
}
