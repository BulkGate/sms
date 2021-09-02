<?php declare(strict_types=1);

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
	private $gate;

	/** @var string */
	private $sender = '';


    /**
     * CountrySenderID constructor.
     * @param string $iso
     * @param int $gate
     * @param string $sender
     */
	public function __construct(string $iso, int $gate = Gate::GATE1, string $sender = '')
	{
		$this->iso = strtolower($iso);
		$this->gate = $gate;
		$this->sender = $sender;
	}


	/**
	 * @return string
	 */
	public function getIso(): string
	{
		return $this->iso;
	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			Gate::ISO => (string) $this->iso,
			Gate::GATE => (int) $this->gate,
			Gate::SENDER => (string) $this->sender,
		];
	}
}
