<?php

/**
 * Transaction file.
 * @author Sam Stenvall <sam@supportersplace.com>
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package yiiga.models
 */

namespace yiiga\models;

/**
 * Describes a Google Analytics transaction.
 * Stores the transactions items as well as the general transaction properties 
 * such as order ID etc.
 */
class Transaction extends \CModel
{

	/**
	 * @var string order ID
	 */
	public $orderId = '';

	/**
	 * @var string affiliation (usually the company name)
	 */
	public $affiliation = '';

	/**
	 * @var string total tax on the order
	 */
	public $tax = '';

	/**
	 * @var float total shipping costs for the order
	 */
	public $shipping = 0.00;

	/**
	 * @var string customer city
	 */
	public $city = '';

	/**
	 * @var string customer state
	 */
	public $state = '';

	/**
	 * @var string customer country
	 */
	public $country = '';

	/**
	 * @var float the total value of the order
	 */
	public $total = 0.00;

	/**
	 * @var TransactionItem[] list of transaction items
	 */
	protected $_items = array();
	
	/**
	 * @implements \CModel
	 * @return array the attributes for this model
	 */
	public function attributeNames()
	{
		return array(
			'orderId',
			'affiliation',
			'tax',
			'shipping',
			'city',
			'state',
			'country',
			'total'
		);
	}

	/**
	 * Adds an item to the transaction
	 * @param TransactionItem $item the item
	 */
	public function addItem($item)
	{
		$item->orderId = $this->orderId;
		$this->_items[] = $item;
	}

	/**
	 * Returns the JavaScript for the items the transaction holds
	 * @return string the script
	 */
	public function getItemsJS()
	{
		$script = '';

		foreach ($this->_items as $item)
			$script .= '_gaq.push('.$item->toJS().');'.PHP_EOL;

		return $script;
	}

	/**
	 * Returns the current object definition as a JavaScript array 
	 * (starting with _addTrans)
	 * @return string the script
	 */
	public function toJS()
	{
		$data = array('_addTrans');
		foreach (array('orderId', 'affiliation', 'total', 'tax', 'shipping', 'city', 'state', 'country') as $name)
			$data[] = $this->{$name};

		return \CJavaScript::encode($data);
	}

}
