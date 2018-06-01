<?php

namespace Lmc\Matej\Model\Command;

use Lmc\Matej\Model\Assertion;

/**
 * UserForget any user in Matej, either by anonymizing or by deleting their entries.
 * Anonymization and deletion is done server-side, and is GDPR-compliant. When anonymizing the data, new user-id is
 * generated server-side and client library won't ever know it.
 */
class UserForget extends AbstractCommand implements UserAwareInterface
{
    const ANONYMIZE = 'anonymize';
    const DELETE = 'delete';
    /** @var string */
    private $userId;
    /** @var string */
    private $method;

    private function __construct($userId, $method)
    {
        $this->setUserId($userId);
        $this->setForgetMethod($method);
    }

    /**
     * Anonymize all user data in Matej.
     * @param mixed $userId
     */
    public static function anonymize($userId)
    {
        return new static($userId, self::ANONYMIZE);
    }

    /**
     * Completely wipe all user data from Matej.
     * @param mixed $userId
     */
    public static function delete($userId)
    {
        return new static($userId, self::DELETE);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getForgetMethod()
    {
        return $this->method;
    }

    protected function setUserId($userId)
    {
        Assertion::typeIdentifier($userId);
        $this->userId = $userId;
    }

    protected function setForgetMethod($method)
    {
        Assertion::choice($method, [self::ANONYMIZE, self::DELETE]);
        $this->method = $method;
    }

    protected function getCommandType()
    {
        return 'user-forget';
    }

    protected function getCommandParameters()
    {
        return ['user_id' => $this->userId, 'method' => $this->method];
    }
}
