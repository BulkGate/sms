<?php declare(strict_types=1);

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */

namespace BulkGate\Sms;

use BulkGate;

class BulkMessage extends BulkGate\Utils\Iterator implements BulkGate\Message\IMessage, \JsonSerializable
{
	use BulkGate\Strict;

	const TYPE = 'bulk-sms';


    /**
     * BulkMessage constructor.
     * @param array $messages
     */
	public function __construct(array $messages)
	{
		foreach ($messages as $message)
		{
			if ($message instanceof Message)
			{
				$this->array[] = $message;
			}
		}
	}


    /**
     * @param Message $message
     */
	public function addMessage(Message $message): void
	{
        $this->array[] = $message;
	}


    /**
     * @param BulkGate\Message\Response $response
     */
	public function setStatus(BulkGate\Message\Response $response): void
    {
        foreach($this->array as $key => $item)
        {
            if($item instanceof Message)
            {
                if(isset($response->response) && is_array($response->response) && isset($response->response[$key]))
                {
                    $item->setStatus((string) ($response->response[$key]['status'] ?? 'error'), (string) ($response->response[$key]['sms_id'] ?? ''), (float) ($response->response[$key]['price'] ?? 0.0), (float) ($response->response[$key]['credit'] ?? 0.0));
                }
                else
                {
                    $item->setStatus('error');
                }
            }
        }
    }


    /**
     * @param int|null $timestamp
     */
    public function schedule(?int $timestamp = null): void
    {
        foreach($this->array as $item)
        {
            if($item instanceof BulkGate\Message\IMessage)
            {
                $item->schedule($timestamp);
            }
        }
    }


    /**
     * @return string
     */
	public function __toString(): string
	{
		$s = '';

		foreach ($this->array as $message)
		{
			$s .= (string) $message . PHP_EOL;
		}
		return $s;
	}


    /**
     * @return array
     */
    public function toArray(): array
	{
		$output = [];

		foreach ($this->array as $message)
		{
			if ($message instanceof BulkGate\Message\IMessage)
			{
				$output[] = $message->toArray();
			}
		}
		return $output;
	}


    /**
     * @return array
     */
	public function jsonSerialize()
    {
        return $this->toArray();
    }


    /**
     * @return int
     */
    public function count(): int
	{
		return (int) count($this->array);
    }


    /**
     * @return string
     */
	public function getType(): string
	{
		return self::TYPE;
	}
}
