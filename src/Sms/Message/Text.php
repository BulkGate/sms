<?php declare(strict_types=1);

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
    public function __construct(?string $text = null, array $variables = [])
    {
        if($text !== null)
        {
            $this->text($text, $variables);
        }
    }


    /**
     * @param string $text
     * @param array $variables
     * @return Text
     */
    public function text(string $text, array $variables = []): self
    {
        $this->fillTemplate($text, $variables);

        return $this;
    }


    /**
     * @return string
     */
    public function getText(): string
    {
        return (string) $this->text;
    }


    /**
     * @param string $text
     * @param array $variables
     * @return Text
     */
    private function fillTemplate(string $text, array $variables = []): self
    {
        $variables = array_combine(
            array_map(function($key){ return '<'.$key.'>'; }, array_keys($variables)),
            $variables
        );

        $this->text = count($variables) > 0 ? strtr($text, $variables) : $text;

        return $this;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getText();
    }


    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return (string) $this;
    }
}
