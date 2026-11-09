<?php

namespace Tests\Feature;

use App\Models\GymClass;
use App\Models\Package;
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

        // Create Program
        $response = $this->actingAs($user)->post('/programs', [
            'nama' => 'Program Uji Coba',
            'deskripsi' => 'Deskripsi program uji coba untuk automated test.',
        ]);
        $response->assertRedirect('/programs');
        $this->assertDatabaseHas('programs', ['nama' => 'Program Uji Coba']);
    }
}
