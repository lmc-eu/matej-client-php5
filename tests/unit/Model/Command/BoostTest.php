<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Exception\DomainException;
use PHPUnit\Framework\TestCase;

class BoostTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeJsonSerializable()
    {
        $boost = Boost::create('valid_to >= NOW()', 2.1);
        $this->assertSame(['query' => 'valid_to >= NOW()', 'multiplier' => 2.1], $boost->jsonSerialize());
    }

    /**
     * @test
     */
    public function shouldNotAllowMultiplierLessThan0()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Provided "-1" is not greater than "0".');
        Boost::create('valid_to >= NOW()', -1);
    }
}
