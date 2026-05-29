<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_sqlite_memory_connection_works(): void
    {
        $connection = DB::getDefaultConnection();
        $this->assertEquals('sqlite', $connection);

        $result = DB::select('select 1 as ok');
        $this->assertEquals(1, $result[0]->ok);
    }
}
