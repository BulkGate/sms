<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate\Message\Response;
use BulkGate\Sms\SenderSettings\ISenderSettings;

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
	public function unicode($unicode = true);


    /**
     * @param bool $flash
     * @return ISender
     */
	public function flash($flash = true);


    /**
     * @param ISenderSettings $senderSettings
     * @return ISender
     */
	public function setSenderSettings(ISenderSettings $senderSettings);


    /**
     * @param string $country
     * @return ISender
     */
	public function setDefaultCountry($country);


    /**
     * @param IMessage $message
     * @return Response
     */
    public function send(IMessage $message);
}
