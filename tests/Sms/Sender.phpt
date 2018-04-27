<?php

/**
 * Test: Nette\Sms\Message
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate;
use BulkGate\Message\Request;
use BulkGate\Message\Response;
use BulkGate\Sms\BulkMessage;
use BulkGate\Message\IConnection;
use BulkGate\Sms\SenderSettings\ISenderSettings;
use BulkGate\Sms\Message;
use BulkGate\Sms\Sender;
use BulkGate\Sms\SenderSettings\Gate;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$connection = new class () implements IConnection
{
    /** @var array */
    private $responses = [];

	public function send(Request $request)
	{
        $this->responses[] = (object) ['action' => $request->getAction(), 'request' => $request->getRawData(), 'response' => null];

        return new Response("{}", 'application/json');
	}


	public function getInfo($delete = false)
	{
        $responses = $this->responses;

        if ($delete)
        {
            $this->responses = [];
        }
        return $responses;
	}
};

$settings = new class () implements ISenderSettings
{
	public function toArray()
	{
		return ['static' => (object) [
		    Gate::ISO    => 'static',
            Gate::GATE   => Gate::GATE3,
            Gate::SENDER => ''
        ]];
	}
};

$message = new Message('420777777777', 'test');

$sender = new Sender($connection);

$sender->send($message);

Assert::equal([(object) [
    'action' => Message::TYPE,
    'request' => [
        'message' => $message,
        'unicode' => false,
        'flash' => false,
        'sender' => []
    ],
    'response' => null
]], $connection->getInfo(true));

Assert::null($message->getPhoneNumber()->getIso());

$sender->flash()->unicode()->setSenderSettings($settings)->setDefaultCountry(BulkGate\Sms\Country::CZECH_REPUBLIC);

$sender->send($message);

Assert::equal([(object) [
    'action' => Message::TYPE,
    'request' => [
        'message' => $message,
        'unicode' => true,
        'flash' => true,
        'sender' => $settings
    ],
    'response' => null
]], $connection->getInfo(true));

Assert::same(strtolower(BulkGate\Sms\Country::CZECH_REPUBLIC), $message->getPhoneNumber()->getIso());

$bulk_message = new BulkMessage([$message]);

$sender->send($bulk_message);

Assert::equal([(object) [
    'action' => BulkMessage::TYPE,
    'request' => [
        'message' => $bulk_message,
        'unicode' => true,
        'flash' => true,
        'sender' => $settings
    ],
    'response' => null
]], $connection->getInfo(true));

$sender->checkPhoneNumbers("420603123456");

Assert::equal([(object) [
	'action' => 'check-phone-numbers',
	'request' => [
		'phoneNumbers' => [new Message\PhoneNumber('420603123456')],
	],
	'response' => null
]], $connection->getInfo(true));

$sender->checkPhoneNumbers(new Message\PhoneNumber("420603123457"));

Assert::equal([(object) [
	'action' => 'check-phone-numbers',
	'request' => [
		'phoneNumbers' => [new Message\PhoneNumber('420603123457')],
	],
	'response' => null
]], $connection->getInfo(true));

$sender->checkPhoneNumbers([new Message\PhoneNumber("420603123458"), '420603123459']);

Assert::equal([(object) [
	'action' => 'check-phone-numbers',
	'request' => [
		'phoneNumbers' => [new Message\PhoneNumber('420603123458'), new Message\PhoneNumber('420603123459')],
	],
	'response' => null
]], $connection->getInfo(true));

