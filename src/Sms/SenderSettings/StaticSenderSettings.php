<?php

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
	public function __construct($type = Gate::GATE_SYSTEM_NUMBER, $value = '')
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
			default:
				throw new InvalidSenderException('Unknown sender type '.$type);
				break;
		}
	}


	public function systemNumber()
	{
		$this->type = Gate::GATE_SYSTEM_NUMBER;
		$this->value = '';
	}


	public function shortCode()
	{
		$this->type = Gate::GATE_SHORT_CODE;
		$this->value = '';
	}


	/**
	 * @param string $value
     * @throws InvalidSenderException
	 */
	public function textSender($value)
	{
		if (strlen((string) $value) >= 3 && strlen((string) $value) <= 11)
		{
			$this->type = Gate::GATE_TEXT_SENDER;
			$this->value = (string) $value;
		}
		else
        {
			throw new InvalidSenderException('Text sender length must be between 3 and 13 characters (' . strlen($value) . ' characters given)');
		}
	}


	/**
	 * @param string $value
     * @throws InvalidSenderException
	 */
	public function ownNumber($value)
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


	/**
	 * @return array
	 */
	public function toArray()
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
	public function jsonSerialize()
    {
        return $this->toArray();
    }
}
