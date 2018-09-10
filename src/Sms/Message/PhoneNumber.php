<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\Message;

use BulkGate;

class PhoneNumber implements \JsonSerializable
{
    use BulkGate\Strict;

    /** @var string */
    private $phone_number = '';

    /** @var string|null */
    private $iso = null;


    /**
     * PhoneNumber constructor.
     * @param string $phone_number
     * @param null|string $iso
     */
    public function __construct($phone_number, $iso = null)
    {
        $this->phoneNumber((string) $phone_number);
        $this->iso($iso);
    }


    /**
     * @param string $phone_number
     * @return PhoneNumber
     */
    public function phoneNumber($phone_number)
    {
        $this->phone_number = $this->formatNumber((string) $phone_number);

        return $this;
    }


    /**
     * @param null|string $iso
     * @return PhoneNumber
     * @throws InvalidPhoneNumberException
     */
    public function iso($iso)
    {
        if ($iso === null || strlen($iso) === 2 || strlen($iso) === 0)
        {
            $this->iso = $iso !== null ? strtolower((string) $iso) : $iso;

            return $this;
        }
        throw new InvalidPhoneNumberException('Invalid message ISO country code - ' . $iso);
    }


    /**
     * @param string $phone_number
     * @return string
     */
    private function formatNumber($phone_number)
    {
        $phone_number = preg_replace(['/ /', '/-/', "/\(/", "/\)/", "/\./", "/\//", "/\\\/", "/,/"], ['', '', '', '', '', '', '', ''], trim((string) $phone_number));

        if (substr($phone_number, 0, 2) === '00')
        {
            $phone_number = substr($phone_number, 2, strlen($phone_number));
        }
        elseif (substr($phone_number, 0, 1) === '+')
        {
            $phone_number = substr($phone_number, 1, strlen($phone_number));
        }

        return $phone_number;
    }


    /**
     * @return null|string
     */
    public function getIso()
    {
        return $this->iso !== null ? ((string) $this->iso) : null;
    }

    public function getPhoneNumber()
    {
        return (string) $this->phone_number;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getPhoneNumber();
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            BulkGate\Message\IMessage::NUMBER => $this->phone_number,
            BulkGate\Message\IMessage::ISO    => $this->iso
        ];
    }
}
