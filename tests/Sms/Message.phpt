<?php

/**
 * Test: Nette\Sms\Message
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
namespace Test;

use BulkGate\Sms;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$number = '+420 (608) 123 456';
$text = 'test message <var1> <var2>';
$variables = ['var1' => 'first', 'var2' => 'second'];
$iso = 'CZ';

$message = new Sms\Message($number, $text);

Assert::type("BulkGate\\Sms\\Message\\PhoneNumber", $message->getPhoneNumber());
Assert::type("BulkGate\\Sms\\Message\\Text", $message->getText());

Assert::same($text, (string) $message->getText());
Assert::same('420608123456', (string) $message->getPhoneNumber());

$message = new Sms\Message(new Sms\Message\PhoneNumber($number, $iso), new Sms\Message\Text($text, $variables));

Assert::same('test message first second', $message->getText()->getText());
Assert::same('420608123456', $message->getPhoneNumber()->getPhoneNumber());
Assert::same('cz', $message->getPhoneNumber()->getIso());

Assert::same('420608123456: test message first second', (string) $message);

Assert::type("BulkGate\\Sms\\Message\\PhoneNumber", $message->getPhoneNumber());
Assert::type("BulkGate\\Sms\\Message\\Text", $message->getText());

Assert::same(Sms\Message::TYPE, $message->getType());

$message->setStatus('accepted', 'id', 1.2, 1405.23);

$message->schedule(1234);

Assert::equal([
    'number' => $message->getPhoneNumber(),
    'text' => $message->getText(),
    'status' => 'accepted',
    'id' => 'id',
    'price' => 1.2,
    'credit' => 1405.23,
    'scheduled' => 1234,
], $message->toArray());

Assert::equal(1405.23, $message->getCredit());

Assert::equal(1.2, $message->getPrice());

Assert::equal('id', $message->getId());

Assert::equal('accepted', $message->getStatus());
