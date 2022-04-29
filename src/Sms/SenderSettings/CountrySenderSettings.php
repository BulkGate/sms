<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

use BulkGate;

class CountrySenderSettings implements ISenderSettings, \JsonSerializable
{
	use BulkGate\Strict;

	/** @var array CountrySenderID */
	private $settings = [];


    /**
     * CountrySenderSettings constructor.
     * @param array $settings
     */
	public function __construct(array $settings = [])
	{
		foreach ($settings as $setting)
		{
			if ($setting instanceof CountrySenderID)
			{
				$this->settings[$setting->getIso()] = $setting;
			}
		}
	}


    /**
     * @param string|array|CountrySenderID $iso
     * @param int $gate
     * @param string $sender
     * @return CountrySenderSettings
     * @throws InvalidGateException
     */
	public function add($iso, int $gate = Gate::GATE1, string $sender = ''): self
	{
		if ($iso instanceof CountrySenderID)
		{
			$this->settings[$iso->getIso()] = $iso;
		}
		elseif (is_array($iso))
        {
			foreach ($iso as $setting)
			{
				if ($setting instanceof CountrySenderID)
				{
					$this->settings[$setting->getIso()] = $setting;
				}
			}
		}
		elseif (strlen($iso) === 2)
        {
			$this->settings[strtolower($iso)] = new CountrySenderID($iso, $gate, $sender);
		}
		else
        {
			throw new InvalidGateException('Invalid message ISO country code');
		}
		return $this;
	}


	/**
	 * @param string $iso
	 * @return bool
	 */
	public function remove(string $iso): bool
	{
		$iso = strtolower($iso);

		if (isset($this->settings[$iso]))
		{
			unset($this->settings[$iso]);
			return true;
		}
		return false;
	}


	/**
	 * @return array
	 */
	public function toArray(): array
	{
		$array = [];

		foreach ($this->settings as $iso => $setting)
		{
			$array[$iso] = $setting->toArray();
		}

		return $array;
	}


	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
