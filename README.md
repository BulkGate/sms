BulkGate SMS - PHP SDK
=============

[![Downloads](https://img.shields.io/packagist/dt/bulkgate/sms.svg)](https://packagist.org/packages/bulkgate/sms)
[![Latest Stable Version](https://img.shields.io/github/release/bulkgate/sms.svg)](https://github.com/bulkgate/sms/releases)
[![License](https://img.shields.io/github/license/bulkgate/sms.svg)](https://github.com/BulkGate/sms/blob/master/LICENSE)

- [Documentation](https://help.bulkgate.com/docs/en/php-sdk-instalation.html)
- [BulkGate portal](https://portal.bulkgate.com/) 
- [BulkGate](www.bulkgate.com)

## Instalation

The easiest way to install [bulkgate/sms](https://packagist.org/packages/bulkgate/sms) into a project is by using [Composer](https://getcomposer.org/) via the command line.

```
composer require bulkgate/sms
```


If you have the package installed just plug in the autoloader.

``` php
require_once __DIR__ . '/vendor/autoload.php';
```

In order to send messages, you need an instance of the `BulkGate\Sms\Sender` class that requires instance dependency on the `BulkGate\Message\Connection` class.

``` php
$connection = new BulkGate\Message\Connection('APPLICATION_ID', 'APPLICATION_TOKEN');

$sender = new BulkGate\Sms\Sender($connection);
```

At this point, you are ready to send a message.

``` php
$message = new BulkGate\Sms\Message('447971700001', 'test message');

$sender->send($message);
```

The `send()` method will send a message `$message`.

## Nette framework

Register the extension to the DI container via NEON

``` neon
extensions:
	bulkgate: BulkGate\Message\Bridges\MessageDI\MessageExtension

bulkgate:
	application_id: <APPLICATION_ID>
	application_token: <APPLICATION_TOKEN>
```

which gives you the class [`BulkGate\Sms\Sender`](php-sdk-sender.md) as a service you can request.

``` php
<?php declare(strict_types=1);

namespace BulkGate\Presenters;

use BulkGate, Nette;

class SdkPresenter extends Nette\Application\UI\Presenter
{
    /** @var BulkGate\Sms\ISender @inject */
    public $sender;

    public function actionDefault()
    {
        $this->sender->send(new BulkGate\Sms\Message('447971700001', 'test message'));
    }
}
```

### Tracy

At the same time, you'll get the extension for [Tracy](https://tracy.nette.org) panel

![bulkgate-sdk-tracy](https://github.com/BulkGate/help/raw/master/website/static/img/sdk-tracy.png)
