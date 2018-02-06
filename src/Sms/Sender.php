<?php declare(strict_types = 1);

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


	public function setDefaultCountry(string $country): Isender
    {
        if(preg_match('~^[a-zA-Z]{2}$~', $country))
        {
            $this->defaultCountry = strtolower($country);
            return $this;
        }
        throw new InvalidIsoCodeException('Invalid ISO 3166-1 alpha-2 format - '.$country);
    }


    /**
     * @param IMessage $message
     * @return Response
     */
	public function send(IMessage $message): Response
	{
	    $this->fillDefaultCountryIso($message);

		return $this->connection->send(new Request($message->getType(), [
			self::MESSAGE => $message,
			self::SENDER => $this->senderSettings instanceof ISenderSettings ? $this->senderSettings : [],
			self::UNICODE => $this->unicode,
			self::FLASH => $this->flash,
		], true));
	}

	private function fillDefaultCountryIso(IMessage $message)
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
