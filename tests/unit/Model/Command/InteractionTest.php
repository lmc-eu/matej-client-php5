<?php

namespace Lmc\Matej\Model\Command;

use ArrayObject;
use Lmc\Matej\Exception\DomainException;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class InteractionTest extends TestCase
{
    const TIMESTAMP = 1511524333;
    use PHPMock;

    /** @before */
    public function initTimeMock()
    {
        $time = $this->getFunctionMock(__NAMESPACE__, 'time');
        $time->expects($this->any())->willReturn(static::TIMESTAMP);
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidAttributeName()
    {
        $command = Interaction::withItem('bookmarks', 'user-id', 'item-id');
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('invalid^*!@" does not match type identifier format requirement');
        $command->setAttribute('invalid^*!@', 'value');
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidAttributeNameInBatch()
    {
        $command = Interaction::withItem('bookmarks', 'user-id', 'item-id');
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"invalid^*!@" does not match type identifier format requirement');
        $command->setAttributes(['valid' => 'value1', 'invalid^*!@' => 'value2']);
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidInteractionType()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"invalid^*!@" does not match type identifier format requirement');
        Interaction::withItem('invalid^*!@', 'user-id', 'item-id');
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidUserId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"invalid^*!@" does not match type identifier format requirement');
        Interaction::withItem('bookmarks', 'invalid^*!@', 'item-id');
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidItemId()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"invalid^*!@" does not match type identifier format requirement');
        Interaction::withItem('bookmarks', 'user-id', 'invalid^*!@');
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithInvalidItemIdAliasName()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('"invalid^*!@" does not match type identifier format requirement');
        Interaction::withAliasedItem('bookmarks', 'user-id', 'invalid^*!@', 'item-id');
    }

    /**
     * @test
     */
    public function shouldRaiseExceptionWithNegativeTimestamp()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Provided "-123" is not greater than "0".');
        Interaction::withItem('bookmarks', 'user-id', 'item-id', -123);
    }

    /**
     * @test
     */
    public function shouldCompileInteractionAttributes()
    {
        $command = Interaction::withItem('bookmarks', 'user-id', 'item-id');
        $command->setAttribute('attribute-1', 'value-1');
        $command->setAttributes(['attribute-2' => 'value-2', 'attribute-3' => 'value-3']);
        $command->setAttribute('attribute-2', 'value-new-2');
        $this->assertEquals($command->jsonSerialize()['parameters']['attributes'], new ArrayObject(['attribute-2' => 'value-new-2', 'attribute-3' => 'value-3']));
    }

    /**
     * @test
     * @dataProvider provideItemIdConstructorParams
     * @runInSeparateProcess so that time() can be mocked safely
     * @param array $constructorParams
     */
    public function shouldBeInstantiableWithItemId(array $constructorParams)
    {
        /** @var Interaction $command */
        $command = forward_static_call_array([Interaction::class, 'withItem'], $constructorParams);
        $this->assertInstanceOf(Interaction::class, $command);
        $this->assertEquals(['type' => 'interaction', 'parameters' => ['interaction_type' => $constructorParams[0], 'user_id' => $constructorParams[1], 'item_id' => $constructorParams[2], 'timestamp' => isset($constructorParams[3]) ? $constructorParams[3] : static::TIMESTAMP, 'attributes' => new ArrayObject()]], $command->jsonSerialize());
        $this->assertSame($constructorParams[1], $command->getUserId());
    }

    /**
     * @test
     * @dataProvider provideItemIdAliasConstructorParams
     * @runInSeparateProcess so that time() can be mocked safely
     * @param array $constructorParams
     */
    public function shouldBeInstantiableWithItemIdAlias(array $constructorParams)
    {
        /** @var Interaction $command */
        $command = forward_static_call_array([Interaction::class, 'withAliasedItem'], $constructorParams);
        $this->assertInstanceOf(Interaction::class, $command);
        $this->assertEquals(['type' => 'interaction', 'parameters' => ['interaction_type' => $constructorParams[0], 'user_id' => $constructorParams[1], 'timestamp' => isset($constructorParams[4]) ? $constructorParams[4] : static::TIMESTAMP, 'attributes' => new ArrayObject(), $constructorParams[2] => $constructorParams[3]]], $command->jsonSerialize());
        $this->assertSame($constructorParams[1], $command->getUserId());
    }

    /**
     * @return array[]
     */
    public function provideItemIdConstructorParams()
    {
        return ['with item_id and required params' => [['bookmarks', 'user123', 'item123']], 'with item_id and optional params' => [['bookmarks', 'user123', 'item123', 123]]];
    }

    /**
     * @return array[]
     */
    public function provideItemIdAliasConstructorParams()
    {
        return ['with single item_id_alias and required params' => [['detailviews', 'user123', 'key', 'value']], 'with single item_id_alias and optional params' => [['bookmarks', 'user123', 'key', 'value', 123]]];
    }
}
