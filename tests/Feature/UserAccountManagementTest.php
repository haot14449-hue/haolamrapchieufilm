<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_regular_users_cannot_access_user_management()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect('/login');

        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get(route('admin.users.index'));
        $response->assertRedirect('/');
    }

    public function test_staff_cannot_access_admin_dashboard_or_user_management()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        // Staff cannot access admin dashboard, redirected to POS
        $responseDashboard = $this->actingAs($staff)->get(route('admin.dashboard'));
        $responseDashboard->assertRedirect(route('pos.index'));

        // Staff cannot access user management, redirected to POS
        $responseUsers = $this->actingAs($staff)->get(route('admin.users.index'));
        $responseUsers->assertRedirect(route('pos.index'));
    }

    public function test_admin_can_view_account_management_page_and_stats()
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Chính']);
        User::factory()->count(2)->create(['role' => 'staff']);
        User::factory()->count(3)->create(['role' => 'customer']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Quản Lý Tài Khoản');
        $response->assertSee('Thêm Nhân Viên Mới');
        $response->assertSee('Admin Chính');
        $response->assertSee('Nhân viên rạp');
    }

    public function test_admin_can_create_a_new_staff_account()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nguyễn Văn Hào (Nhân viên Quầy vé)',
            'email' => 'haonv@hctv.com',
            'phone' => '0912345678',
            'role' => 'staff',
            'password' => 'StaffPass123@',
            'password_confirmation' => 'StaffPass123@',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Nguyễn Văn Hào (Nhân viên Quầy vé)',
            'email' => 'haonv@hctv.com',
            'phone' => '0912345678',
            'role' => 'staff',
        ]);

        $createdUser = User::where('email', 'haonv@hctv.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check('StaffPass123@', $createdUser->password));
        $this->assertNotNull($createdUser->email_verified_at);
        $this->assertTrue($createdUser->isStaff());
    }

    public function test_admin_can_change_staff_account_name_and_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create([
            'name' => 'Tên Cũ',
            'email' => 'staff_old@hctv.com',
            'role' => 'staff',
            'phone' => '0111111111',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $staff->id), [
            'name' => 'Tên Mới - Quản Lý Ca',
            'email' => 'staff_new@hctv.com',
            'phone' => '0999999999',
            'role' => 'staff',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $staff->refresh();
        $this->assertEquals('Tên Mới - Quản Lý Ca', $staff->name);
        $this->assertEquals('staff_new@hctv.com', $staff->email);
        $this->assertEquals('0999999999', $staff->phone);
    }

    public function test_admin_can_change_staff_password_via_update_form()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create([
            'name' => 'Nhân viên A',
            'email' => 'staff_a@hctv.com',
            'role' => 'staff',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $staff->id), [
            'name' => 'Nhân viên A',
            'email' => 'staff_a@hctv.com',
            'role' => 'staff',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $staff->refresh();
        $this->assertTrue(Hash::check('newpassword123', $staff->password));
    }

    public function test_admin_can_change_password_via_quick_password_endpoint()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create([
            'name' => 'Nhân viên B',
            'email' => 'staff_b@hctv.com',
            'role' => 'staff',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.password.update', $staff->id), [
            'password' => 'quickResetPass999',
            'password_confirmation' => 'quickResetPass999',
        ]);

        $response->assertSessionHas('success');
        $staff->refresh();
        $this->assertTrue(Hash::check('quickResetPass999', $staff->password));
    }

    public function test_admin_cannot_delete_their_own_account()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_a_staff_account()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $staff->id));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $staff->id]);
    }
}
