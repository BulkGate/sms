<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

interface ISenderSettings
{
	/**
	 * @return array
	 */
	public function toArray();
}
