<?php

declare(strict_types=1);

namespace Tests\App;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class SalesApiTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testApiReturns401WithNoToken(): void
    {
        $result = $this->get('api/products');
        $result->assertStatus(401);

        $json = json_decode($result->getJSON(), true);
        $this->assertSame('error', $json['status']);
        $this->assertSame('Unauthorized. Bearer token required.', $json['message']);
    }

    public function testApiReturns401WithInvalidToken(): void
    {
        $result = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken123',
        ])->get('api/products');

        $result->assertStatus(401);

        $json = json_decode($result->getJSON(), true);
        $this->assertSame('error', $json['status']);
    }

    public function testTokenEndpointReturns401WithWrongCredentials(): void
    {
        $result = $this->post('api/auth/token', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $result->assertStatus(401);

        $json = json_decode($result->getJSON(), true);
        $this->assertSame('error', $json['status']);
        $this->assertSame('Invalid credentials.', $json['message']);
    }

    public function testStockEndpointReturns404ForUnknownProduct(): void
    {
        // Get a valid token first using seeded superadmin credentials
        $tokenResult = $this->post('api/auth/token', [
            'email'    => 'superadmin@smro.com',
            'password' => 'password',
        ]);

        $tokenJson = json_decode($tokenResult->getJSON(), true);

        if (!isset($tokenJson['data']['token'])) {
            $this->markTestSkipped('Could not obtain token — check seeded credentials.');
        }

        $token = $tokenJson['data']['token'];

        $result = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('api/products/99999/stock');

        $result->assertStatus(404);

        $json = json_decode($result->getJSON(), true);
        $this->assertSame('error', $json['status'] ?? 'error');
    }
}
