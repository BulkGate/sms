<?php

/**
 * Test: Nette\Sms\SenderSettings\CountrySenderID
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate;
use BulkGate\Sms\SenderSettings\CountrySenderID;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


$country = new CountrySenderID('CZ', 1, '');

Assert::same([
	'iso' => 'cz',
	'gate' => 1,
	'sender' => '',
], $country->toArray());

$country = new CountrySenderID('CZ', 2, '');
Assert::same([
	'iso' => 'cz',
	'gate' => 2,
	'sender' => '',
], $country->toArray());

Assert::exception(function () {
	new CountrySenderID('CZ', -1, '');
}, "BulkGate\\Sms\\SenderSettings\\InvalidGateException", 'Gate must be in interval <0, 4>');

Assert::exception(function () {
	new CountrySenderID('CZ', 5, '');
}, "BulkGate\\Sms\\SenderSettings\\InvalidGateException", 'Gate must be in interval <0, 4>');

Assert::exception(function () {
	new CountrySenderID('CZ', 125, '');
}, "BulkGate\\Sms\\SenderSettings\\InvalidGateException", 'Gate must be in interval <0, 4>');
