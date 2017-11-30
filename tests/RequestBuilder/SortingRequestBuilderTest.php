<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Matej\RequestBuilder\SortingRequestBuilder
 * @covers \Lmc\Matej\RequestBuilder\AbstractRequestBuilder
 */
class SortingRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldBuildRequestWithCommands()
    {
        $sortingCommand = Sorting::create('userId1', ['itemId1', 'itemId2']);
        $builder = new SortingRequestBuilder($sortingCommand);
        $interactionCommand = Interaction::detailView('userId1', 'itemId1');
        $builder->setInteraction($interactionCommand);
        $userMergeCommand = UserMerge::mergeFromSourceToTargetUser('sourceId1', 'targetId1');
        $builder->setUserMerge($userMergeCommand);
        $request = $builder->build();
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame(RequestMethodInterface::METHOD_POST, $request->getMethod());
        $this->assertSame('/sorting', $request->getPath());
        $requestData = $request->getData();
        $this->assertCount(3, $requestData);
        $this->assertSame($interactionCommand, $requestData[0]);
        $this->assertSame($userMergeCommand, $requestData[1]);
        $this->assertSame($sortingCommand, $requestData[2]);
    }

    /** @test */
    public function shouldThrowExceptionWhenSendingCommandsWithoutRequestManager()
    {
        $builder = new SortingRequestBuilder(Sorting::create('userId1', ['itemId1', 'itemId2']));
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Instance of RequestManager must be set to request builder');
        $builder->send();
    }

    /** @test */
    public function shouldSendRequestViaRequestManager()
    {
        $requestManagerMock = $this->createMock(RequestManager::class);
        $requestManagerMock->expects($this->once())->method('sendRequest')->with($this->isInstanceOf(Request::class))->willReturn(new Response(0, 0, 0, 0));
        $builder = new SortingRequestBuilder(Sorting::create('userId1', ['itemId1', 'itemId2']));
        $builder->setRequestManager($requestManagerMock);
        $builder->send();
    }
}