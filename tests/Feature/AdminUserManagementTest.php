<?php

namespace Tests\Feature;

use App\Mail\AdminDirectMessageMail;
use App\Models\Country;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CountrySeeder::class);
        Storage::fake('public');
    }

    public function test_sugar_baby_registration_requires_at_least_one_photo(): void
    {
        $country = Country::where('iso_code', 'AR')->first() ?? Country::first();

        $response = $this->post('/register', [
            'name' => 'Sugar Baby Test',
            'email' => 'sb@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'sugar_baby',
            'gender' => 'female',
            'birth_date' => '2000-01-01',
            'country_id' => $country->id,
            // photo omitida
        ]);

        $response->assertSessionHasErrors(['photo']);
        $this->assertDatabaseMissing('users', ['email' => 'sb@example.com']);
    }

    public function test_sugar_baby_registration_succeeds_with_photo(): void
    {
        $country = Country::where('iso_code', 'AR')->first() ?? Country::first();
        $file = UploadedFile::fake()->image('sb-avatar.jpg', 600, 600);

        $response = $this->post('/register', [
            'name' => 'Sugar Baby With Photo',
            'email' => 'sb_photo@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'sugar_baby',
            'gender' => 'female',
            'birth_date' => '2000-01-01',
            'country_id' => $country->id,
            'photo' => $file,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'sb_photo@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('sugar_baby', $user->user_type);
        $this->assertEquals(1, $user->photos()->count());

        $photo = $user->photos()->first();
        $this->assertTrue($photo->is_primary);
        $this->assertEquals('approved', $photo->moderation_status);
    }

    public function test_sugar_daddy_can_register_without_photo(): void
    {
        $country = Country::where('iso_code', 'AR')->first() ?? Country::first();

        $response = $this->post('/register', [
            'name' => 'Sugar Daddy No Photo',
            'email' => 'sd@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'sugar_daddy',
            'gender' => 'male',
            'birth_date' => '1985-05-15',
            'country_id' => $country->id,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'sd@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(0, $user->photos()->count());
    }

    public function test_super_admin_can_delete_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
            'user_type' => 'sugar_baby',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.moderation.users.destroy', $user), [
                'reason' => 'Usuario inactivo y sin foto',
            ])
            ->assertRedirect(route('admin.moderation.users'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action_type' => 'delete_user',
            'auditable_id' => $user->id,
        ]);
    }

    public function test_super_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.moderation.users.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_super_admin_can_send_premade_message_to_user(): void
    {
        Mail::fake();
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'user',
            'user_type' => 'sugar_baby',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.moderation.users.message', $user), [
                'template_key' => 'photo_required',
                'subject' => '📸 Acción requerida: Sube una foto a tu perfil de Big-Dad',
                'message' => 'Hola '.$user->name.', necesitamos que subas una foto para activar tu perfil.',
                'action_url' => route('profile.photos.index'),
                'action_text' => 'Subir mi foto de perfil',
            ]);

        $response->assertSessionHas('success');

        Mail::assertQueued(AdminDirectMessageMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email)
                && str_contains($mail->subjectLine, '📸 Acción requerida')
                && str_contains($mail->messageBody, 'necesitamos que subas una foto');
        });

        Notification::assertSentTo($user, AdminMessageNotification::class);

        $this->assertDatabaseHas('admin_audit_logs', [
            'admin_id' => $admin->id,
            'action_type' => 'send_admin_message',
            'auditable_id' => $user->id,
        ]);
    }
}
