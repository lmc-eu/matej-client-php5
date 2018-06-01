<?php

namespace Lmc\Matej\RequestBuilder;

use Fig\Http\Message\RequestMethodInterface;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Assertion;
use Lmc\Matej\Model\Command\UserForget;
use Lmc\Matej\Model\Request;

class ForgetRequestBuilder extends AbstractRequestBuilder
{
    const ENDPOINT_PATH = '/forget';
    /** @var UserForget[] */
    protected $users = [];

    /** @return $this */
    public function addUser(UserForget $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * @param UserForget[] $users
     * @return $this
     */
    public function addUsers(array $users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    public function build()
    {
        if (empty($this->users)) {
            throw new LogicException('At least one UserForget command must be added to the builder before sending the request');
        }
        Assertion::batchSize($this->users);

        return new Request(static::ENDPOINT_PATH, RequestMethodInterface::METHOD_POST, $this->users, $this->requestId);
    }
}
