<?php

declare(strict_types=1);

namespace App;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\UserModel;

class AuthTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        
        $userModel = new UserModel();
        $userModel->insert([
            'name'          => 'Test User',
            'email'         => 'test@example.com',
            'password_hash' => 'password123',
            'role'          => 'staff',
            'is_active'     => 1
        ]);
    }

    public function testLoginWithCorrectCredentials()
    {
        $result = $this->withSession()->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
            csrf_token() => csrf_hash()
        ]);

        $result->assertRedirectTo('/dashboard');
        $this->assertTrue(session()->get('is_logged_in'));
        $this->assertEquals('test@example.com', session()->get('email'));
    }

    public function testLoginWithWrongPassword()
    {
        $result = $this->withSession()->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
            csrf_token() => csrf_hash()
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error', 'Invalid email or password.');
        $this->assertFalse(session()->has('is_logged_in'));
    }

    public function testLogoutDestroysSession()
    {
        session()->set([
            'is_logged_in' => true,
            'email'        => 'test@example.com'
        ]);

        $result = $this->withSession()->get('/logout');

        $result->assertRedirectTo('/login');
        $this->assertFalse(session()->has('is_logged_in'));
    }
}
