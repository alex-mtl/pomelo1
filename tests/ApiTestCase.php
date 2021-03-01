<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;

abstract class ApiTestCase extends TestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    protected static $migrated = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->migrateAndSeedDB();
    }

    /**
     * Migrate and seed DB
     */
    protected function migrateAndSeedDB(): void
    {
        if (self::$migrated) {
            return;
        }
        // migrate and seed database once for all tests
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        self::$migrated = true;
    }
}
