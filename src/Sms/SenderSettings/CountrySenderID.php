<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

use BulkGate;

class CountrySenderID
{
	use BulkGate\Strict;

	/** @var string */
	private $iso;

	/** @var int */
	private $gate = Gate::GATE1;

	/** @var string */
	private $sender = '';


    /**
     * CountrySenderID constructor.
     * @param string $iso
     * @param int $gate
     * @param string $sender
     * @throws InvalidGateException
     */
	public function __construct($iso, $gate = Gate::GATE1, $sender = '')
	{
		$this->iso = strtolower((string) $iso);
		$this->gate = (int) $gate;
		$this->sender = (string) $sender;

		if ((int) $this->gate < Gate::GATE1 || (int) $this->gate > Gate::GATE5)
		{
			throw new InvalidGateException('Gate must be in interval <0, 4>');
		}
	}


	/**
	 * @return string
	 */
	public function getIso()
	{
		return $this->iso;
	}


	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			Gate::ISO => (string) $this->iso,
			Gate::GATE => (int) $this->gate,
			Gate::SENDER => (string) $this->sender,
		];
	}
}
