<?php

namespace Lmc\Matej\Model\Command;

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
     * @dataProvider provideConstructorName
     * @runInSeparateProcess so that time() can be mocked safely
     * @param mixed $constructorName
     * @param mixed $expectedInteractionType
     * @param array $extraConstructorParams
     */
    public function shouldBeInstantiableViaNamedConstructors($constructorName, $expectedInteractionType, array $extraConstructorParams)
    {
        $constructorParams = array_merge(['exampleUserId', 'exampleItemId'], $extraConstructorParams);
        /** @var Interaction $command */
        $command = forward_static_call_array([Interaction::class, $constructorName], $constructorParams);
        $this->assertInstanceOf(Interaction::class, $command);
        $this->assertSame(['type' => 'interaction', 'parameters' => ['interaction_type' => $expectedInteractionType, 'user_id' => 'exampleUserId', 'item_id' => 'exampleItemId', 'timestamp' => isset($extraConstructorParams[2]) ? $extraConstructorParams[2] : static::TIMESTAMP, 'value' => isset($extraConstructorParams[0]) ? $extraConstructorParams[0] : 1.0, 'context' => isset($extraConstructorParams[1]) ? $extraConstructorParams[1] : 'default']], $command->jsonSerialize());
        $this->assertSame('exampleUserId', $command->getUserId());
    }

    /**
     * @return array[]
     */
    public function provideConstructorName()
    {
        return ['detailView with only required params' => ['detailView', 'detailviews', []], 'detailView with optional params' => ['detailView', 'detailviews', [0.5, 'myContextFoo', 1337333666]], 'purchase with only required params' => ['purchase', 'purchases', []], 'purchase with optional params' => ['purchase', 'purchases', [0.0, 'myContextBar', 1337333666]], 'bookmark with only required params' => ['bookmark', 'bookmarks', []], 'bookmark with optional params' => ['bookmark', 'bookmarks', [0.1337, 'myContextBaz', 1337333666]], 'rating with only required params' => ['rating', 'ratings', []], 'rating with optional params' => ['rating', 'ratings', [0.9, 'myContextBan', 1337333666]]];
    }
}
