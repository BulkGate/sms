<?php

/**
 * Test: Nette\Sms\SenderSettings\StaticSenderSettings
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate\Sms\SenderSettings\Gate, BulkGate\Sms\SenderSettings\StaticSenderSettings;
use Tester\Assert;


require __DIR__ . '/../../bootstrap.php';


$settings = new StaticSenderSettings();

$system_number = ['static' => [
	Gate::ISO => 'static',
	Gate::GATE => Gate::GATE_SYSTEM_NUMBER,
	Gate::SENDER => '',
]];

$short_code = ['static' => [
	Gate::ISO => 'static',
	Gate::GATE => Gate::GATE_SHORT_CODE,
	Gate::SENDER => '',
]];

$text_sender = ['static' => [
	Gate::ISO => 'static',
	Gate::GATE => Gate::GATE_TEXT_SENDER,
	Gate::SENDER => 'Nette',
]];

$own_number = ['static' => [
	Gate::ISO => 'static',
	Gate::GATE => Gate::GATE_OWN_NUMBER,
	Gate::SENDER => '420777666555',
]];

Assert::same($system_number, $settings->toArray());

$settings->systemNumber();
Assert::same($system_number, $settings->toArray());

$settings->textSender('Nette');
Assert::same($text_sender, $settings->toArray());

$settings->shortCode();
Assert::same($short_code, $settings->toArray());

$settings->ownNumber('420777666555');
Assert::same($own_number, $settings->toArray());

foreach (['Nette framework', 'NF'] as $sender) {
	Assert::exception(function () use ($settings, $sender) {
		$settings->textSender($sender);
	}, "BulkGate\\Sms\\SenderSettings\\InvalidSenderException", 'Text sender length must be between 3 and 13 characters (' . strlen($sender) . ' characters given)');
}

foreach (['', '   '] as $sender) {
	Assert::exception(function () use ($settings, $sender) {
		$settings->ownNumber($sender);
	}, "BulkGate\\Sms\\SenderSettings\\InvalidSenderException", 'Empty own number value');
}

$settings = new StaticSenderSettings(Gate::GATE_SYSTEM_NUMBER);
Assert::same($system_number, $settings->toArray());

$settings = new StaticSenderSettings(Gate::GATE_TEXT_SENDER, 'Nette');
Assert::same($text_sender, $settings->toArray());

$settings = new StaticSenderSettings(Gate::GATE_SHORT_CODE);
Assert::same($short_code, $settings->toArray());

$settings = new StaticSenderSettings(Gate::GATE_OWN_NUMBER, '420777666555');
Assert::same($own_number, $settings->toArray());

Assert::exception(function () {
	new StaticSenderSettings('Nette_sender');
}, "BulkGate\\Sms\\SenderSettings\\InvalidSenderException", 'Unknown sender type '.'Nette_sender');
