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

    /** @var array */
    private $variables = [];


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
     * @param bool $save_variables
     * @return Text
     */
    public function text($text, array $variables = [], $save_variables = false)
    {
        $this->fillTemplate((string) $text, $variables);

        if((bool) $save_variables)
        {
            $this->variables = $variables;
        }

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
     * @param null|string $key
     * @return array|string|int|bool
     */
    public function getVariables($key = null)
    {
        if($key !== null)
        {
            return isset($this->variables[$key]) ? $this->variables : null;
        }
        return $this->variables;
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
