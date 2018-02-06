<?php declare(strict_types = 1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use Nette;

class SmsException extends Nette\InvalidStateException
{
}

class InvalidMessageException extends SmsException
{
}

class InvalidIsoCodeException extends SmsException
{
}
