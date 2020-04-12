<?php

namespace Neliserp\Core;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// TODO: This class should be located in 'tests/' folder,
// but class 'not found' when others package tryint to extend.
abstract class CrudTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
    }
}
