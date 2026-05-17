<?php

namespace Tests\Unit;

use App\Services\ExternalUserService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalUserServiceTest extends TestCase
{
    public function test_it_keeps_existing_external_username(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/users' => Http::response([
                ['id' => 1, 'username' => 'usuario01'],
            ]),
        ]);

        $this->assertSame(
            'usuario01',
            app(ExternalUserService::class)->resolveLogUsername('usuario01')
        );
    }

    public function test_it_marks_unknown_username_when_external_api_responds(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/users' => Http::response([
                ['id' => 1, 'username' => 'usuario01'],
            ]),
        ]);

        $this->assertSame(
            'desconocido',
            app(ExternalUserService::class)->resolveLogUsername('otro')
        );
    }

    public function test_it_preserves_username_when_external_api_is_unavailable(): void
    {
        Http::fake([
            'http://127.0.0.1:8001/api/users' => Http::response([], 500),
        ]);

        $this->assertSame(
            'usuario01',
            app(ExternalUserService::class)->resolveLogUsername('usuario01')
        );
    }
}
