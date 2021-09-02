<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

use BulkGate;

class StaticSenderSettings implements ISenderSettings, \JsonSerializable
{
	use BulkGate\Strict;

	/** @var string */
	private $type = Gate::GATE_SYSTEM_NUMBER;

	/** @var string */
	private $value = '';


    /**
     * StaticSenderSettings constructor.
     * @param string $type
     * @param string $value
     * @throws InvalidSenderException
     */
	public function __construct(string $type = Gate::GATE_SYSTEM_NUMBER, string $value = '')
	{
		switch ($type) {
			case Gate::GATE_SYSTEM_NUMBER:
				$this->systemNumber();
				break;
			case Gate::GATE_SHORT_CODE:
				$this->shortCode();
				break;
			case Gate::GATE_TEXT_SENDER:
				$this->textSender($value);
				break;
			case Gate::GATE_OWN_NUMBER:
				$this->ownNumber($value);
				break;
			case Gate::GATE_MOBILE_CONNECT:
				$this->mobileConnect($value);
				break;
			default:
				throw new InvalidSenderException('Unknown sender type '.$type);
				break;
		}
	}


	public function systemNumber(): void
	{
		$this->type = Gate::GATE_SYSTEM_NUMBER;
		$this->value = '';
	}


	public function shortCode(): void
	{
		$this->type = Gate::GATE_SHORT_CODE;
		$this->value = '';
	}


    /**
     * @param string $value
     * @throws InvalidSenderException
     */
	public function textSender(string $value): void
	{
		if (strlen((string) $value) >= 3 && strlen((string) $value) <= 11)
		{
			$this->type = Gate::GATE_TEXT_SENDER;
			$this->value = (string) $value;
		}
		else
        {
			throw new InvalidSenderException('Text sender length must be between 3 and 11 characters (' . strlen($value) . ' characters given)');
		}
	}


    /**
     * @param string $value
     * @throws InvalidSenderException
     */
	public function ownNumber(string $value): void
	{
		if (strlen((string) trim($value)) > 0)
		{
			$this->type = Gate::GATE_OWN_NUMBER;
			$this->value = (string) $value;
		}
		else
        {
			throw new InvalidSenderException('Empty own number value');
		}
	}


	public function mobileConnect(string $value): void
	{
		$this->type = Gate::GATE_MOBILE_CONNECT;
		$this->value = $value;
	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return [
		    'static' => [
		        Gate::ISO => 'static',
                Gate::GATE => $this->type,
                Gate::SENDER => $this->value
            ]
        ];
	}


    /**
     * @return array
     */
	public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
