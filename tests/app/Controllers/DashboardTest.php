<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Controllers\DashboardController;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * PHPUnit Feature Test — Dashboard Views
 *
 * Verifies that each role's dashboard route:
 *   1. Returns HTTP 200
 *   2. Does not throw an exception or fatal error
 *   3. Renders role-specific content markers
 *
 * Run: php spark test --filter DashboardViewTest
 *
 * @internal
 * @covers \App\Controllers\DashboardController
 */
class DashboardViewTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    // ── Session helper ────────────────────────────────────────────────────────

    /**
     * Build a fake session payload for a given role.
     *
     * @param  string $role  superadmin | manager | staff
     * @return array<string, mixed>
     */
    private function sessionFor(string $role): array
    {
        return [
            'isLoggedIn' => true,
            'user_id'    => match ($role) {
                'superadmin' => 1,
                'manager'    => 2,
                default      => 3,
            },
            'name'       => match ($role) {
                'superadmin' => 'Alice Admin',
                'manager'    => 'Bob Manager',
                default      => 'Carol Staff',
            },
            'email'      => "{$role}@smro.test",
            'role'       => $role,
        ];
    }

    // ── SuperAdmin ────────────────────────────────────────────────────────────

    /**
     * SuperAdmin dashboard returns 200 with no exceptions.
     */
    public function testSuperAdminDashboardReturnsOk(): void
    {
        $result = $this
            ->withSession($this->sessionFor('superadmin'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('Exception');
        $result->assertDontSee('Fatal');
    }

    /**
     * SuperAdmin dashboard renders role-specific content.
     */
    public function testSuperAdminDashboardRendersExpectedContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('superadmin'))
            ->get('/dashboard');

        $result->assertStatus(200);

        // These strings should appear in the superadmin view
        $result->assertSee('Super Admin Dashboard');
        $result->assertSee('Total Products');
        $result->assertSee('Active Users');
        $result->assertSee('Low Stock Alerts');
    }

    /**
     * SuperAdmin dashboard does NOT render manager/staff-only content.
     */
    public function testSuperAdminDashboardDoesNotRenderOtherRoleContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('superadmin'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('My Sales Today');       // staff-only
        $result->assertDontSee('Pending Returns');      // manager-only
    }

    // ── Manager ───────────────────────────────────────────────────────────────

    /**
     * Manager dashboard returns 200 with no exceptions.
     */
    public function testManagerDashboardReturnsOk(): void
    {
        $result = $this
            ->withSession($this->sessionFor('manager'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('Exception');
        $result->assertDontSee('Fatal');
    }

    /**
     * Manager dashboard renders role-specific content.
     */
    public function testManagerDashboardRendersExpectedContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('manager'))
            ->get('/dashboard');

        $result->assertStatus(200);

        $result->assertSee('Manager Dashboard');
        $result->assertSee('Revenue This Month');
        $result->assertSee('Top Products');
    }

    /**
     * Manager dashboard does NOT render superadmin/staff-only content.
     */
    public function testManagerDashboardDoesNotRenderOtherRoleContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('manager'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('My Sales Today');       // staff-only
        $result->assertDontSee('System Settings');      // superadmin quick link
    }

    // ── Staff ─────────────────────────────────────────────────────────────────

    /**
     * Staff dashboard returns 200 with no exceptions.
     */
    public function testStaffDashboardReturnsOk(): void
    {
        $result = $this
            ->withSession($this->sessionFor('staff'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('Exception');
        $result->assertDontSee('Fatal');
    }

    /**
     * Staff dashboard renders role-specific content.
     */
    public function testStaffDashboardRendersExpectedContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('staff'))
            ->get('/dashboard');

        $result->assertStatus(200);

        $result->assertSee('My Sales Today');
        $result->assertSee('New Sale');
        $result->assertSee('My Transactions Today');
    }

    /**
     * Staff dashboard does NOT render privileged content.
     */
    public function testStaffDashboardDoesNotRenderPrivilegedContent(): void
    {
        $result = $this
            ->withSession($this->sessionFor('staff'))
            ->get('/dashboard');

        $result->assertStatus(200);
        $result->assertDontSee('Super Admin Dashboard'); // superadmin-only
        $result->assertDontSee('Active Users');          // superadmin metric
        $result->assertDontSee('Revenue This Month');    // manager metric
    }

    // ── Auth Guard ────────────────────────────────────────────────────────────

    /**
     * Unauthenticated request to /dashboard must redirect (302) to login.
     */
    public function testUnauthenticatedUserIsRedirectedFromDashboard(): void
    {
        $result = $this->get('/dashboard');

        // Expect a redirect — not a 200 or exception
        $this->assertContains(
            $result->response()->getStatusCode(),
            [301, 302],
            'Expected redirect for unauthenticated dashboard access.'
        );
    }
}