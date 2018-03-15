<?php

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms\Message;

use BulkGate;

class Text implements \JsonSerializable
{
    use BulkGate\Strict;

    /** @var string */
    private $text = '';


    /**
     * Text constructor.
     * @param null|string $text
     * @param array $variables
     */
    public function __construct($text = null, array $variables = [])
    {
        if($text !== null)
        {
            $this->text((string) $text, $variables);
        }
    }


    /**
     * @param string $text
     * @param array $variables
     * @return Text
     */
    public function text($text, array $variables = [])
    {
        $this->fillTemplate((string) $text, $variables);

        return $this;
    }


    /**
     * @return string
     */
    public function getText()
    {
        return (string) $this->text;
    }


    /**
     * @param string $text
     * @param array $variables
     * @return Text
     */
    private function fillTemplate($text, array $variables = [])
    {
        $variables = array_combine(
            array_map(function($key){ return '<'.$key.'>'; }, array_keys($variables)),
            $variables
        );

        $this->text = count($variables) > 0 ? strtr((string) $text, $variables) : ((string) $text);

        return $this;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }


    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return (string) $this;
    }
}
