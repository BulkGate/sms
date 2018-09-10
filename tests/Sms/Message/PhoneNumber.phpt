<?php

/**
 * Test: BulkGate\Message\Connection
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate;
use Tester;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$phone = new BulkGate\Sms\Message\PhoneNumber('603123456', BulkGate\Sms\Country::CZECH_REPUBLIC);

Assert::same('603123456', (string) $phone);
Assert::same('603123456', $phone->getPhoneNumber());
Assert::same('cz', $phone->getIso());

Assert::same("420608123456", (string) $phone->phoneNumber("+420 608 123 456"));
Assert::same("420608123456", (string) $phone->phoneNumber("  (420) 608/123-456"));
Assert::same("420608123456", (string) $phone->phoneNumber("(420) 608.123,456  "));
Assert::same("420608123456", (string) $phone->phoneNumber("00(420) 608.123,456"));

$phone = new BulkGate\Sms\Message\PhoneNumber('420603123456');

Assert::null($phone->getIso());

$phone->iso(BulkGate\Sms\Country::SLOVAKIA);

Assert::same('sk', $phone->getIso());

$phone->iso(null);

Assert::null($phone->getIso());

Assert::exception(function () use ($phone) {
    $phone->iso('a');
}, "BulkGate\\Sms\\Message\\InvalidPhoneNumberException");

Assert::exception(function () use ($phone) {
    $phone->iso('cze');
}, "BulkGate\\Sms\\Message\\InvalidPhoneNumberException");

$phone->iso(BulkGate\Sms\Country::CZECH_REPUBLIC);

Assert::same('{"'.BulkGate\Message\IMessage::NUMBER.'":"420603123456","'.BulkGate\Message\IMessage::ISO.'":"cz"}', BulkGate\Utils\Json::encode($phone));
