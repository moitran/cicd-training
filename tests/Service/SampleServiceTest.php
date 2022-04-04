<?php

namespace App\Tests\Service;

use App\Service\SampleService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class SampleServiceTest
 * @package App\Tests\Service
 */
class SampleServiceTest extends KernelTestCase
{
    /**
     * @test
     */
    public function sample()
    {
        $mock = $this->getMockBuilder(SampleService::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /**
         * @var $mock SampleService
         */
        $this->assertEquals(3, $mock->sum(1, 2));
        $this->assertEquals(4, $mock->sum(1, 2));
    }
}
