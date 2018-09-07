<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;
use BulkGate\Sms\SenderSettings\{ISenderSettings};

interface ISender
{
	const MESSAGE = 'message';

	const SENDER = 'sender';

	const UNICODE = 'unicode';

	const FLASH = 'flash';


	/**
	 * @param bool $unicode
	 * @return ISender
	 */
	public function unicode(bool $unicode = true): ISender;


    /**
     * @param bool $flash
     * @return ISender
     */
	public function flash(bool $flash = true): ISender;


    /**
     * @param ISenderSettings $senderSettings
     * @return ISender
     */
	public function setSenderSettings(ISenderSettings $senderSettings): ISender;


    /**
     * @param string $country
     * @return ISender
     */
	public function setDefaultCountry(string $country): Isender;


    /**
     * @param BulkGate\Message\IMessage $message
     * @return BulkGate\Message\Response
     */
    public function send(BulkGate\Message\IMessage $message): BulkGate\Message\Response;


	/**
	 * @param array|Message\PhoneNumber|string $phoneNumbers
	 * @param null|string $iso
	 * @return  BulkGate\Message\Response
	 */
    public function checkPhoneNumbers($phoneNumbers, ?string $iso = null): BulkGate\Message\Response;
}
