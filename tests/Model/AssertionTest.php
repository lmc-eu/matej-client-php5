<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Matej\Model\Assertion
 */
class AssertionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideValidTypeIdentifiers
     * @param mixed $typeIdentifier
     */
    public function shouldAssertValidTypeIdentifier($typeIdentifier)
    {
        $this->assertTrue(Assertion::typeIdentifier($typeIdentifier));
    }

    /**
     * @return array[]
     */
    public function provideValidTypeIdentifiers()
    {
        return ['single character' => ['a'], 'lower/upper case combination' => ['FOObar'], 'numbers, dashes' => ['foo-123'], 'cases, numbers, uderscore, dash' => ['fOoO_13-37'], 'starts with number' => ['123-foo'], 'number as string' => ['666333666333'], 'max length (100 characters)' => [str_repeat('a', 100)]];
    }

    /**
     * @test
     * @dataProvider provideInvalidTypeIdentifiers
     * @param mixed $typeIdentifier
     * @param string $expectedExceptionMessage
     */
    public function shouldAssertInvalidTypeIdentifier($typeIdentifier, $expectedExceptionMessage)
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        Assertion::typeIdentifier($typeIdentifier);
    }

    /**
     * @return array[]
     */
    public function provideInvalidTypeIdentifiers()
    {
        $formatExceptionMessage = 'does not match type identifier format requirement';
        $lengthExceptionMessage = 'is too long, it should have no more than 100 characters';

        return ['empty' => ['', $formatExceptionMessage], 'special national characters' => ['föbär', $formatExceptionMessage], 'at character' => ['user@email', $formatExceptionMessage], 'integer' => [333666, $formatExceptionMessage], 'over max length (>100 characters)' => [str_repeat('a', 101), $lengthExceptionMessage]];
    }
}
