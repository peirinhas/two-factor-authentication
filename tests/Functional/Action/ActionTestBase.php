<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use App\Tests\Functional\TestBase;

class ActionTestBase extends TestBase
{
    protected string $endpointAuthenticate;

    public function setUp()
    {
        parent::setUp();

        $this->endpointAuthenticate = '/api/v1/authenticates';
    }
}
