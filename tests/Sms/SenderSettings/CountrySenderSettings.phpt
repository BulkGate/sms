<?php

/**
 * Test: Nette\Sms\SenderSettings\CountrySenderSettings
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate;
use BulkGate\Sms\SenderSettings\CountrySenderID, BulkGate\Sms\SenderSettings\CountrySenderSettings;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


$array = [
	'cz' => ['iso' => 'cz', 'gate' => 1, 'sender' => ''],
	'sk' => ['iso' => 'sk', 'gate' => 2, 'sender' => ''],
	'gb' => ['iso' => 'gb', 'gate' => 1, 'sender' => ''],
	'de' => ['iso' => 'de', 'gate' => 3, 'sender' => 'Nette'],
	'us' => ['iso' => 'us', 'gate' => 1, 'sender' => ''],
	'ru' => ['iso' => 'ru', 'gate' => 4, 'sender' => '420777444555'],
];

$settingUS = new CountrySenderID('US', 1, '');
$settingGB = new CountrySenderID('GB', 1, '');

$settings = new CountrySenderSettings([new CountrySenderID('CZ', 1, ''), new CountrySenderID('RU', 4, '420777444555')]);

$settings->add('SK', 2, '');
$settings->add(new CountrySenderID('DE', 3, 'Nette'));
$settings->add([$settingGB, $settingUS]);

Assert::equal($array, $settings->toArray());

Assert::true($settings->remove('SK'));
Assert::false($settings->remove('SK'));
unset($array['sk']);

Assert::equal($array, $settings->toArray());

Assert::exception(function () use ($settings) {
	$settings->add('cze');
}, "BulkGate\\Sms\\SenderSettings\\InvalidGateException", 'Invalid message ISO country code');
