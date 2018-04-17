<?php

namespace Lmc\Matej\Model;

use Lmc\Matej\Exception\DomainException;
use Lmc\Matej\Exception\LogicException;
use Lmc\Matej\Model\Command\AbstractCommand;

/**
 * @method static bool allTypeIdentifier(mixed $value) Assert value is valid Matej type identifier for all values
 */
class Assertion extends \Assert\Assertion
{
    const MAX_TYPE_IDENTIFIER_LENGTH = 100;
    const MAX_BATCH_SIZE = 1000;
    protected static $exceptionClass = DomainException::class;

    /**
     * Assert value is valid Matej type identifier
     *
     * @param mixed $value
     */
    public static function typeIdentifier($value)
    {
        static::regex($value, '/^[0-9A-Za-z-_]+$/', 'Value "%s" does not match type identifier format requirement (must contain only of alphanumeric chars,' . ' dash or underscore)');
        static::maxLength($value, self::MAX_TYPE_IDENTIFIER_LENGTH);

        return true;
    }

    /**
     * Assert Commands batch size to Matej is not over size limit.
     *
     * @param AbstractCommand[] $commands
     */
    public static function batchSize(array $commands)
    {
        static::lessOrEqualThan(count($commands), self::MAX_BATCH_SIZE, 'Request contains %s commands, but at most %s is allowed in one request.');

        return true;
    }

    /**
     * Assert that provided classname is an instance or subclass of Response
     * @param mixed $wantedClass
     */
    public static function isResponseClass($wantedClass)
    {
        if (!is_a($wantedClass, Response::class, true) && !is_subclass_of($wantedClass, Response::class)) {
            throw LogicException::forClassNotExtendingOtherClass($wantedClass, Response::class);
        }

        return true;
    }
}
