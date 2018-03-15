<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

use BulkGate;

class InvalidGateException extends BulkGate\Sms\SmsException
{
}

class InvalidSenderException extends BulkGate\Sms\SmsException
{
}