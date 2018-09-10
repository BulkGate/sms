<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;
use BulkGate\Message\{IConnection, Response, Request};
use BulkGate\Sms\SenderSettings\{ISenderSettings};

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
	public function unicode(bool $unicode = true): ISender
	{
		$this->unicode = $unicode;

		return $this;
	}


    /**
     * @param bool $flash
     * @return ISender
     */
	public function flash(bool $flash = true): ISender
	{
		$this->flash = $flash;

		return $this;
	}


    /**
     * @param ISenderSettings $senderSettings
     * @return ISender
     */
    public function setSenderSettings(ISenderSettings $senderSettings): ISender
	{
		$this->senderSettings = $senderSettings;

		return $this;
	}


	/**
	 * @param string $country
	 * @return ISender
	 * @throws InvalidIsoCodeException
	 */
	public function setDefaultCountry(string $country): ISender
    {
        if(preg_match('~^[a-zA-Z]{2}$~', $country))
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
	public function send(BulkGate\Message\IMessage $message): Response
	{
	    $this->fillDefaultCountryIso($message);

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
            $message->setStatus((string) ($response->status ?? 'error'), (string) ($response->sms_id ?? ''), (float) ($response->price ?? 0.0), (float) ($response->credit ?? 0.0));
        }

        return $response;
	}


	/**
	 * @param array|Message\PhoneNumber|string $phoneNumbers
	 * @param null|string $iso
	 * @return Response
	 * @throws BulkGate\Exception
	 */
	public function checkPhoneNumbers($phoneNumbers, ?string $iso = null): Response
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
	private function fillDefaultCountryIso(BulkGate\Message\IMessage $message): void
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
