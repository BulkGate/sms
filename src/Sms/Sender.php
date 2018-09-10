<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;
use BulkGate\Message\IConnection, BulkGate\Message\Response, BulkGate\Message\Request;
use BulkGate\Sms\SenderSettings\ISenderSettings;

class Sender implements ISender
{
	use BulkGate\Strict;

	/** @var IConnection */
	private $connection;

	/** @var ISenderSettings|null  */
	private $senderSettings;

	/** @var bool */
	private $unicode = false;

	/** @var bool */
	private $flash = false;

	/** @var null|string */
	private $defaultCountry = null;

    /**
     * Sender constructor.
     * @param IConnection $connection
     */
	public function __construct(IConnection $connection)
	{
		$this->connection = $connection;
	}


    /**
     * @param bool $unicode
     * @return ISender
     */
	public function unicode($unicode = true)
	{
		$this->unicode = (bool) $unicode;

		return $this;
	}


    /**
     * @param bool $flash
     * @return ISender
     */
	public function flash($flash = true)
	{
		$this->flash = (bool) $flash;

		return $this;
	}


    /**
     * @param ISenderSettings $senderSettings
     * @return ISender
     */
    public function setSenderSettings(ISenderSettings $senderSettings)
	{
		$this->senderSettings = $senderSettings;

		return $this;
	}


	/**
	 * @param string $country
	 * @return ISender
	 * @throws InvalidIsoCodeException
	 */
	public function setDefaultCountry($country)
    {
        if(preg_match('~^[a-zA-Z]{2}$~', (string) $country))
        {
            $this->defaultCountry = strtolower($country);
            return $this;
        }
        throw new InvalidIsoCodeException('Invalid ISO 3166-1 alpha-2 format - '.$country);
    }


    /**
     * @param BulkGate\Message\IMessage $message
     * @return Response
     */
	public function send(BulkGate\Message\IMessage $message)
	{
	    $this->fillDefaultCountryIso($message);

	    /** @var Response $response */
		$response = $this->connection->send(new Request($message->getType(), [
			self::MESSAGE => $message,
			self::SENDER => $this->senderSettings instanceof ISenderSettings ? $this->senderSettings : [],
			self::UNICODE => $this->unicode,
			self::FLASH => $this->flash,
		], true));

        if($message instanceof BulkMessage)
        {
            if($response->isSuccess())
            {
                $message->setStatus($response);
            }
        }
        else if($message instanceof Message)
        {
            $message->setStatus(
                isset($response->status) ? $response->status : 'error',
                isset($response->sms_id) ? $response->sms_id : '',
                isset($response->price) ? $response->price : 0.0,
                isset($response->credit) ? $response->credit : 0.0
            );
        }

        return $response;
	}


	/**
	 * @param array|Message\PhoneNumber|string $phoneNumbers
	 * @param null|string $iso
	 * @return Response
	 * @throws BulkGate\Exception
	 */
	public function checkPhoneNumbers($phoneNumbers, $iso = null)
	{
		$data = [];

		if(is_string($phoneNumbers))
		{
			$data[] = new BulkGate\Sms\Message\PhoneNumber($phoneNumbers, $iso);
		}
		elseif(is_array($phoneNumbers))
		{
			foreach($phoneNumbers as $phoneNumber)
			{
				if($phoneNumber instanceof BulkGate\Sms\Message\PhoneNumber)
				{
					$data[] = $phoneNumber;
				}
				elseif(is_string($phoneNumber))
				{
					$data[] = new BulkGate\Sms\Message\PhoneNumber($phoneNumber, $iso);
				}
			}
		}
		elseif ($phoneNumbers instanceof BulkGate\Sms\Message\PhoneNumber)
		{
			$data[] = $phoneNumbers;
		}

		if(count($data) > 0)
		{
			return $this->connection->send(new Request('check-phone-numbers', ['phoneNumbers' => $data], true));
		}
		throw new InvalidPhoneNumbersException("Request does not contain any phone numbers (string|array|BulkGate\\Sms\\Message\\PhoneNumber)");
	}


	/**
	 * @param BulkGate\Message\IMessage $message
	 */
	private function fillDefaultCountryIso(BulkGate\Message\IMessage $message)
    {
        if($this->defaultCountry !== null)
        {
            if($message instanceof Message)
            {
                if($message->getPhoneNumber()->getIso() === null)
                {
                    $message->getPhoneNumber()->iso($this->defaultCountry);
                }
            }
            else if($message instanceof BulkMessage)
            {
                /** @var Message $m */
                foreach($message as $m)
                {
                    if($m->getPhoneNumber()->getIso() === null)
                    {
                        $m->getPhoneNumber()->iso($this->defaultCountry);
                    }
                }
            }
        }
    }
}
