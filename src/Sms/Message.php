<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;
use BulkGate\Sms\Message\PhoneNumber;
use BulkGate\Sms\Message\Text;

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
     * @param string|PhoneNumber|null $phone_number
     * @param string|Text|null $text
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
	public function phoneNumber($phone_number, ?string $iso = null): self
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
	public function text($text, array $variables = []): self
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
    public function setStatus(string $status, ?string $id = null, float $price = 0.0, float $credit = 0.0): self
    {
        $this->status = $status;
        $this->id = $id;
        $this->price = $price;
        $this->credit = $credit;

        return $this;
    }


    /**
     * @return Message\PhoneNumber
     */
	public function getPhoneNumber(): BulkGate\Sms\Message\PhoneNumber
    {
        return $this->phone_number;
    }


    /**
     * @return Message\Text
     */
	public function getText(): BulkGate\Sms\Message\Text
    {
        return $this->text;
    }


    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }


    /**
     * @return string|null
     */
    public function getId():? string
    {
        return $this->id;
    }


    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }


    /**
     * @return float
     */
    public function getCredit(): float
    {
        return $this->credit;
    }


    /**
     * @param int|null $timestamp
     */
    public function schedule(?int $timestamp = null): void
    {
        $this->scheduled = $timestamp;
    }


    /**
     * @return string
     */
	public function __toString(): string
	{
		return (string) $this->phone_number . ': ' . (string) $this->text;
	}


    /**
     * @return array
     */
	public function toArray(): array
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
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

    /**
     * @return string
     */
	public function getType(): string
	{
		return self::TYPE;
	}
}
