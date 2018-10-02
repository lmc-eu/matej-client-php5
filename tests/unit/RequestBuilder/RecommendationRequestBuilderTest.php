<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Http\RequestManager;
use Lmc\Matej\Model\Command\Interaction;
use Lmc\Matej\Model\Command\Sorting;
use Lmc\Matej\Model\Command\UserMerge;
use Lmc\Matej\Model\Command\UserRecommendation;
use Lmc\Matej\Model\Request;
use Lmc\Matej\Model\Response;
use Lmc\Matej\Model\Response\RecommendationsResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lmc\Matej\RequestBuilder\AbstractRequestBuilder
 * @covers \Lmc\Matej\RequestBuilder\RecommendationRequestBuilder
 */
class RecommendationRequestBuilderTest extends TestCase
{
    /** @test */
    public function shouldBuildRequestWithCommands()
    {
        $recommendationsCommand = UserRecommendation::create('userId1', 5, 'test-scenario', 0.5, 3600);
        $builder = new RecommendationRequestBuilder($recommendationsCommand);
        $interactionCommand = Interaction::detailView('sourceId1', 'itemId1');
        $builder->setInteraction($interactionCommand);
        $userMergeCommand = UserMerge::mergeFromSourceToTargetUser('sourceId1', 'userId1');
        $builder->setUserMerge($userMergeCommand);
        $builder->setRequestId('custom-request-id-foo');
        $request = $builder->build();
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame(RequestMethodInterface::METHOD_POST, $request->getMethod());
        $this->assertSame('/recommendations', $request->getPath());
        $requestData = $request->getData();
        $this->assertCount(3, $requestData);
        $this->assertSame($interactionCommand, $requestData[0]);
        $this->assertSame($userMergeCommand, $requestData[1]);
        $this->assertSame($recommendationsCommand, $requestData[2]);
        $this->assertSame('custom-request-id-foo', $request->getRequestId());
        $this->assertSame(RecommendationsResponse::class, $request->getResponseClass());
    }

    /** @test */
    public function shouldThrowExceptionWhenSendingCommandsWithoutRequestManager()
    {
        $recommendationsCommand = UserRecommendation::create('userId1', 5, 'test-scenario', 0.5, 3600);
        $builder = new RecommendationRequestBuilder($recommendationsCommand);
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

    /** @test */
    public function shouldThrowExceptionWhenInteractionIsForUnrelatedUser()
    {
        $builder = new RecommendationRequestBuilder($recommendationsCommand = UserRecommendation::create('userId1', 5, 'scenario', 0.5, 3600));
        $builder->setInteraction(Interaction::purchase('different-user', 'itemId1'));
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('User in Interaction command ("different-user") must be the same as user in UserRecommendation command ' . '("userId1")');
        $builder->build();
    }

    /** @test */
    public function shouldThrowExceptionWhenMergeIsForUnrelatedUser()
    {
        $builder = new RecommendationRequestBuilder($recommendationsCommand = UserRecommendation::create('userId1', 5, 'scenario', 0.5, 3600));
        $builder->setUserMerge(UserMerge::mergeInto('different-user', 'userId1'));
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('User in UserMerge command ("different-user") must be the same as user in UserRecommendation command' . ' ("userId1")');
        $builder->build();
    }

    /**
     * ([interaction], [user merge], [recommendation]): (A, A -> B, B)
     *
     * @test
     * @dataProvider provideCorrectSequenceOfUsers
     * @param mixed $interactionUser
     * @param mixed $sourceUserToBeDeleted
     * @param mixed $targetUserId
     * @param mixed $recommendationUser
     */
    public function shouldPassOnCorrectSequenceOfUsersWhenMerging($interactionUser, $sourceUserToBeDeleted, $targetUserId, $recommendationUser)
    {
        $interactionCommand = Interaction::purchase($interactionUser, 'test-item-id');
        $userMergeCommand = UserMerge::mergeFromSourceToTargetUser($sourceUserToBeDeleted, $targetUserId);
        $recommendationsCommand = UserRecommendation::create($recommendationUser, 5, 'scenario', 0.5, 3600);
        $builder = new RecommendationRequestBuilder($recommendationsCommand);
        $builder->setUserMerge($userMergeCommand);
        $builder->setInteraction($interactionCommand);
        $this->assertInstanceOf(Request::class, $builder->build());
    }

    /**
     * ([interaction], [user merge], [recommendation]): (A, B -> A, B)
     *
     * @test
     */
    public function shouldFailOnIncorrectSequenceOfUsersWhenMerging()
    {
        $interactionCommand = Interaction::purchase('test-user-a', 'test-item-id');
        $userMergeCommand = UserMerge::mergeFromSourceToTargetUser('test-user-b', 'test-user-a');
        $recommendationsCommand = UserRecommendation::create('test-user-b', 5, 'scenario', 0.5, 3600);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Source user in UserMerge command ("test-user-b") must be the same as user in Interaction command ("test-user-a")');
        $builder = new RecommendationRequestBuilder($recommendationsCommand);
        $builder->setUserMerge($userMergeCommand);
        $builder->setInteraction($interactionCommand);
        $builder->build();
    }

    /**
     * @return array[]
     */
    public function provideCorrectSequenceOfUsers()
    {
        return ['(A, A -> B, B)' => ['test-user-a', 'test-user-a', 'test-user-b', 'test-user-b'], '(B, A -> B, B)' => ['test-user-b', 'test-user-a', 'test-user-b', 'test-user-b']];
    }
}
