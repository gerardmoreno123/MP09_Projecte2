<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public const TESTED_BY = self::class;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'super-admin']);
    }

    public function test_isSuperAdmin()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isSuperAdmin());

        $user->givePermissionTo('super-admin');

        $this->assertTrue($user->isSuperAdmin());
    }
}
