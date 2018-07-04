<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;

class Message implements IMessage, \JsonSerializable
{
	use BulkGate\Strict;

	const TYPE = 'transaction-sms';

	/** @var BulkGate\Sms\Message\PhoneNumber */
	private $phone_number;

	/** @var BulkGate\Sms\Message\Text */
	private $text;
	
	/** @var BulkGate\Sms\Message\Text
	private $vairables;

	/** @var string */
	private $status = 'preparation';

	/** @var string|null */
	private $id = null;

	/** @var float */
	private $price = 0.0;


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
	$this->variables = $variables;
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
     * @return $this
     */
    public function setStatus(string $status, ?string $id = null, float $price = 0.0): self
    {
        $this->status = $status;
        $this->id = $id;
        $this->price = $price;

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
     * @return Message\Text variables
     */
	public function getVariables($key = null)
    {
        return isset($this->variables[$key]) ? $this->variables[$key] : null;
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
            self::ID => $this->id
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
