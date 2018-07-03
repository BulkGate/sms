<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

interface IMessage
{
	const NUMBER = 'number';

	const TEXT = 'text';

	const PRICE = 'price';

	const STATUS = 'status';

	const ID = 'id';

	const ISO = 'iso';

	const VARIABLES = 'variables';


    /**
     * @return string
     */
	public function __toString(): string ;


    /**
     * @return string
     */
	public function getType(): string;


    /**
     * @return array
     */
    public function toArray(): array;
}
