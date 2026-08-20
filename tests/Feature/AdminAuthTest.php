<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * Regression coverage for Phase 3's admin-auth standardization: every admin
 * route except login/password-reset now sits behind the admin.auth
 * middleware at the route-group level, instead of each controller manually
 * repeating (or, in AdminOrderController's case, using the built-in
 * auth:admin middleware, which redirects to the customer login route
 * instead of /admin/login since `login` is a named customer route here).
 */
class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public static function protectedAdminRoutesProvider(): array
    {
        return [
            'dashboard' => ['admin.dashboard'],
            'categories.index' => ['admin.categories.index'],
            'dresses.index' => ['admin.dresses.index'],
            'orders.index' => ['admin.orders.index'],
            'hero-slides.index' => ['admin.hero-slides.index'],
            'settings.edit' => ['admin.settings.edit'],
        ];
    }

    /**
     * @dataProvider protectedAdminRoutesProvider
     */
    public function test_guest_is_redirected_to_admin_login_not_customer_login(string $routeName): void
    {
        $response = $this->get(route($routeName));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_admin_logout_requires_authentication(): void
    {
        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_password_reset_token_is_stored_in_its_own_table_not_the_customer_table(): void
    {
        $admin = Admin::factory()->create();

        Password::broker('admins')->sendResetLink(['email' => $admin->email]);

        $this->assertDatabaseHas('admin_password_reset_tokens', ['email' => $admin->email]);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $admin->email]);
    }
}
