<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $user;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::where('email', '=', 'admin123@example.com')->get()->first();
        $this->withoutExceptionHandling();
    }
}
