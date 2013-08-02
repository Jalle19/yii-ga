yii-ga
======

Google Analytics extension for the Yii framework. It supports general tracking as well as tracking e-commerce transactions.

See https://developers.google.com/analytics/devguides/ for general information about Google Analytics integration.

Installation
------------

Install the application using Composer and make sure you've included Composer's autoloader in your bootstrap file. After that you will need to add the following to your application configuration:

```php
// change this path if necessary
Yii::setPathOfAlias('yiiga', realpath(__DIR__.'/../../vendor/jalle19/yii-ga/src/yiiga'));
...
return array(
	...
	'components'=>array(
		...
		'ga'=>array(
			'class'=>'yiiga\components\GoogleAnalytics',
			'accountId'=>'UA-XXXXXXX-X',
			'cookieDomain'=>'www.example.com', // optional
			'currency'=>'euro', // only needed if you're going to use e-commerce transactions
		),
	),
),

```

Usage
-----

To register basic tracking support, add `Yii::app()->ga->registerTracking();` to your main layout file (assuming you want to track the whole site).

To register e-commerce transactions, adapt the following snippet to your needs (see the class files for which properties are available for transactions and transaction items):

```php
<?php

/* @var $order Order */

// Create a new transaction
$transaction = new yiiga\models\Transaction();
$transaction->orderId = $order->id;
$transaction->city = $order->city;
$transaction->country = $order->country;
$transaction->total = $order->total;
$transaction->tax = $order->tax;

// Add the order items to the transaction
foreach ($order->items as $orderItem)
{
	$item = new yiiga\models\TransactionItem();
	$item->sku = $orderItem->sku;
	$item->name = $orderItem->name;
	$item->price = ($orderItem->price + $orderItem->tax);
	$item->quantity = $orderItem->quantity;

	$transaction->addItem($item);
}

// Register the transaction
Yii::app()->ga->addTransaction($transaction);

```

License
-------

This application is licensed under the [New BSD License](http://www.opensource.org/licenses/bsd-license.php)

Credits
-------

Thanks to [@crisu83](https://github.com/Crisu83/) for giving me some pointers!


