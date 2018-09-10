<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;

class Message implements BulkGate\Message\IMessage, \JsonSerializable
{
	use BulkGate\Strict;

	const TYPE = 'transaction-sms';

	/** @var BulkGate\Sms\Message\PhoneNumber */
	private $phone_number;

	/** @var BulkGate\Sms\Message\Text */
	private $text;

	/** @var string */
	private $status = 'preparation';

	/** @var string|null */
	private $id = null;

	/** @var float */
	private $price = 0.0;

	/** @var float */
	private $credit = 0.0;

	/** @var int|null */
	private $scheduled = null;


    /**
     * Message constructor.
     * @param null $phone_number
     * @param null $text
     */
	public function __construct($phone_number = null, $text = null)
	{
		$this->phoneNumber($phone_number)->text($text);
	}


    /**
     * @param $phone_number
     * @param null|string $iso
     * @return Message
     */
	public function phoneNumber($phone_number, $iso = null)
	{
        if($phone_number instanceof BulkGate\Sms\Message\PhoneNumber)
        {
            $this->phone_number = $phone_number;
        }
        else
        {
            $this->phone_number = new BulkGate\Sms\Message\PhoneNumber($phone_number, $iso);
        }

		return $this;
	}


    /**
     * @param $text
     * @param array $variables
     * @return Message
     */
	public function text($text, array $variables = [])
	{
        if($text instanceof BulkGate\Sms\Message\Text)
        {
            $this->text = $text;
        }
        else
        {
            $this->text = new BulkGate\Sms\Message\Text($text, $variables);
        }

        return $this;
	}


    /**
     * @param string $status
     * @param string|null $id
     * @param float $price
     * @param float $credit
     * @return $this
     */
    public function setStatus($status, $id = null, $price = 0.0, $credit = 0.0)
    {
        $this->status = (string) $status;
        $this->id = $id !== null ? (string) $id : null;
        $this->price = (float) $price;
        $this->credit = (float) $credit;

        return $this;
    }


    /**
     * @return Message\PhoneNumber
     */
	public function getPhoneNumber()
    {
        return $this->phone_number;
    }


    /**
     * @return Message\Text
     */
	public function getText()
    {
        return $this->text;
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return (string) $this->status;
    }


    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id !== null ? (string) $this->id : null;
    }


    /**
     * @return float
     */
    public function getPrice()
    {
        return (float) $this->price;
    }


    /**
     * @return float
     */
    public function getCredit()
    {
        return (float) $this->credit;
    }


    /**
     * @param int|null $timestamp
     */
    public function schedule($timestamp = null)
    {
        $this->scheduled = is_int($timestamp) ? (int) $timestamp : null;
    }


    /**
     * @return string
     */
	public function __toString()
	{
		return (string) $this->phone_number . ': ' . (string) $this->text;
	}


    /**
     * @return array
     */
	public function toArray()
    {
        return [
            self::NUMBER => $this->phone_number,
            self::TEXT => $this->text,
            self::STATUS => $this->status,
            self::PRICE => $this->price,
            self::CREDIT => $this->credit,
            self::ID => $this->id,
            self::SCHEDULED => $this->scheduled
        ];
    }

    /**
     * @return array
     */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

    /**
     * @return string
     */
	public function getType()
	{
		return self::TYPE;
	}
}
