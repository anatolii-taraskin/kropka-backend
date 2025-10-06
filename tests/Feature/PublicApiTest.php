<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicApiTest extends TestCase
{
    public function test_status_endpoint_returns_success_response(): void
    {
        $response = $this->getJson('/api/status');

        $response->assertOk()->assertJson([
            'status' => 'ok',
        ]);
    }
}
