<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\SenderSettings;

use BulkGate;

class Gate
{
	use BulkGate\Strict;

	const ISO = 'iso';

	const GATE = 'gate';

	const SENDER = 'sender';

	const GATE_SYSTEM_NUMBER = 'gSystem';

	const GATE_SHORT_CODE = 'gShort';

	const GATE_TEXT_SENDER = 'gText';

	const GATE_OWN_NUMBER = 'gOwn';

	const GATE1 = 0;

	const GATE2 = 1;

	const GATE3 = 2;

	const GATE4 = 3;

	const GATE5 = 4;
}
