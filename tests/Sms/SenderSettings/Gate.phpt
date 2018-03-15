<?php

/**
 * Test: BulkGate\Sms\SenderSettings\Gate
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace Test;

use BulkGate\Sms\SenderSettings\Gate;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

Assert::same('iso', Gate::ISO);
Assert::same('gate', Gate::GATE);
Assert::same('sender', Gate::SENDER);

Assert::same('gSystem', Gate::GATE_SYSTEM_NUMBER);
Assert::same('gShort', Gate::GATE_SHORT_CODE);
Assert::same('gText', Gate::GATE_TEXT_SENDER);
Assert::same('gOwn', Gate::GATE_OWN_NUMBER);

Assert::same(0, Gate::GATE1);
Assert::same(1, Gate::GATE2);
Assert::same(2, Gate::GATE3);
Assert::same(3, Gate::GATE4);
Assert::same(4, Gate::GATE5);
