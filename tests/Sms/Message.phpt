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
