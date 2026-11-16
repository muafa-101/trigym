<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TriGymTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('TriGym');
        $response->assertSee('Fasilitas Kami');
        $response->assertSee('Pilihan Kelas');
        $response->assertSee('Paket Membership');
    }

    public function test_signin_and_signup_pages_load(): void
    {
        $response = $this->get('/signin');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');

        $response = $this->get('/signup');
        $response->assertStatus(200);
        $response->assertSee('Gabung TriGym Hari Ini');
    }

    public function test_member_authentication_and_dashboard(): void
    {
        $user = User::where('email', 'abdoel@example.com')->first();
        $this->assertNotNull($user);

        // Login
        $response = $this->post('/signin', [
            'email' => 'abdoel@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('/dashboard');

        // Dashboard
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('Status Gym :');

        // Generate check-in code
        $response = $this->actingAs($user)->postJson('/generate-code');
        $response->assertStatus(200);
        $response->assertJsonStructure(['code']);

        // Programs index
        $response = $this->actingAs($user)->get('/programs');
        $response->assertStatus(200);
        $response->assertSee('Program Management');

        // Create Program Form
        $response = $this->actingAs($user)->get('/programs/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Program Baru');

        // Create Program Store
        $response = $this->actingAs($user)->post('/programs', [
            'nama' => 'Program Uji Coba',
            'deskripsi' => 'Deskripsi program uji coba untuk automated test.',
        ]);
        $response->assertRedirect('/programs');
        $this->assertDatabaseHas('programs', ['nama' => 'Program Uji Coba']);
        $program = Program::where('nama', 'Program Uji Coba')->first();

        // Edit Program Form
        $response = $this->actingAs($user)->get('/programs/'.$program->id.'/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Program');

        // Update Program
        $response = $this->actingAs($user)->post('/programs/update/'.$program->id, [
            'nama' => 'Program Uji Coba Updated',
            'deskripsi' => 'Deskripsi program setelah update.',
        ]);
        $response->assertRedirect('/programs');
        $this->assertDatabaseHas('programs', ['nama' => 'Program Uji Coba Updated']);

        // Destroy Program
        $response = $this->actingAs($user)->post('/programs/delete/'.$program->id);
        $response->assertRedirect('/programs');
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
    }

    public function test_user_management_crud(): void
    {
        $admin = User::where('email', 'abdoel@example.com')->first();
        $this->assertNotNull($admin);

        // Users Index
        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Pengelolaan Member');

        // Users Create Form
        $response = $this->actingAs($admin)->get('/users/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Member Baru');

        // Users Store
        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Member Baru Test',
            'email' => 'memberbaru@example.com',
            'password' => 'secret1234',
            'status' => 'Aktif',
            'package_id' => 1,
        ]);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['email' => 'memberbaru@example.com']);
        $newUser = User::where('email', 'memberbaru@example.com')->first();

        // Users Edit Form
        $response = $this->actingAs($admin)->get('/users/'.$newUser->id.'/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Informasi Member');

        // Users Update
        $response = $this->actingAs($admin)->post('/users/update/'.$newUser->id, [
            'name' => 'Member Baru Updated',
            'email' => 'memberbaru@example.com',
            'status' => 'Nonaktif',
            'package_id' => 1,
        ]);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['id' => $newUser->id, 'name' => 'Member Baru Updated', 'status' => 'Nonaktif']);

        // Users Destroy
        $response = $this->actingAs($admin)->post('/users/delete/'.$newUser->id);
        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }
}
