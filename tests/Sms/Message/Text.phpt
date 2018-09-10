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


$test = function ($expected, BulkGate\Sms\Message\Text $text)
{
    Assert::same($expected, (string) $text);

    Assert::same($expected, $text->getText());

    Assert::same('"'.$expected.'"', BulkGate\Utils\Json::encode($text));
};

$test('test Lukáš-- Lukáš Piják', new BulkGate\Sms\Message\Text("test <first_name>-- <first_name> <last_name>", ['first_name' => 'Lukáš', 'last_name' => 'Piják']));

$test('test', new BulkGate\Sms\Message\Text("test", ['first_name' => 'Lukáš', 'last_name' => 'Piják']));

$test('test', new BulkGate\Sms\Message\Text("test"));

$test('<hello>', new BulkGate\Sms\Message\Text("<hello>"));

$test('hi', new BulkGate\Sms\Message\Text("<hello>", ['hello' => 'hi']));

$test('HiSms', new BulkGate\Sms\Message\Text("<hello><world>", ['hello' => 'Hi', 'world' => 'Sms']));

$empty = new BulkGate\Sms\Message\Text();

$test('HiSms', $empty->text("<hello><world>", ['hello' => 'Hi', 'world' => 'Sms']));

$variables = new BulkGate\Sms\Message\Text("<hello><world>", ['hello' => 'Hi', 'world' => 'Sms']);
Assert::equal([], $variables->getVariables());

$variables->text("<hello><world>", ['hello' => 'Hi', 'world' => 'Sms'], true);
Assert::equal(['hello' => 'Hi', 'world' => 'Sms'], $variables->getVariables());


