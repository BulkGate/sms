<?php

/**
 * Test: Nette\Sms\BulkMessage
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate\Sms\BulkMessage, BulkGate\Sms\Message;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$number = '420777888666';
$text = 'test message';
$iso = 'cz';
$phone_number = new Message\PhoneNumber($number, $iso);

$number_new = '777999888';
$text_new = 'Hello Nette!';
$iso_new = 'SK';
$phone_number_new = new Message\PhoneNumber($number_new, $iso_new);

$bulk = new BulkMessage([new Message($phone_number, $text), new Message($phone_number_new, $text_new), [], 'abc', 123]);

Assert::same('bulk-sms', $bulk->getType());

Assert::equal(
	[
		['number' => $phone_number, 'text' => $bulk->get(0)->getText(), 'status' => 'preparation', 'price' => 0.0, 'credit' => 0.0, 'id' => null, 'scheduled' => null],
		['number' => $phone_number_new, 'text' => $bulk->get(1)->getText(), 'status' => 'preparation', 'price' => 0.0, 'credit' => 0.0, 'id' => null, 'scheduled' => null],
	],
	$bulk->toArray()
);

Assert::equal(2, $bulk->count());

$message = new Message($number, $text);
$bulk->addMessage($message);

$bulk->schedule(123456);

Assert::equal(
	[
		['number' => $phone_number, 'text' => new Message\Text($text), 'status' => 'preparation', 'price' => 0.0, 'credit' => 0.0, 'id' => null, 'scheduled' => 123456],
		['number' => $phone_number_new, 'text' => new Message\Text($text_new), 'status' => 'preparation', 'price' => 0.0, 'credit' => 0.0, 'id' => null, 'scheduled' => 123456],
		$message->toArray(),
	],
	$bulk->toArray()
);

Assert::equal(3, $bulk->count());

Assert::same(
	'420777888666: test message' . PHP_EOL .
	'777999888: Hello Nette!' . PHP_EOL .
	'420777888666: test message' . PHP_EOL,
	(string) $bulk);

