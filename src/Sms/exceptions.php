<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;

class SmsException extends BulkGate\Exception
{
}

class InvalidMessageException extends SmsException
{
}

class InvalidIsoCodeException extends SmsException
{
}

class InvalidPhoneNumbersException extends SmsException
{
}
