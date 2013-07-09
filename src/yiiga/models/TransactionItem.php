<?php

/**
 * TransactionItem file.
 * @author Sam Stenvall <sam@supportersplace.com>
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package yiiga.models
 */

namespace yiiga\models;

/**
 * Describes a Google Analytics transaction item
 * @property $orderId the order ID
 */
class TransactionItem extends \CModel
{

	/**
	 * @var string order ID
	 */
	protected $_orderId;

	/**
	 * @var string item SKU/code
	 */
	public $sku = '';

	/**
	 * @var string item name
	 */
	public $name = '';

	/**
	 * @var string item category name
	 */
	public $category = '';

	/**
	 * @var float item price
	 */
	public $price = 0.00;

	/**
	 * @var integer item quantity
	 */
	public $quantity = 0;
	
	/**
	 * @implements \CModel
	 * @return array the attributes for this model
	 */
	public function attributeNames()
	{
		return array(
			'orderId',
			'sku',
			'name',
			'category',
			'price',
			'quantity',
		);
	}

	/**
	 * Returns the current object definition as a JavScript array, 
	 * (starting with _addItem)
	 * @return string the script
	 */
	public function toJS()
	{
		$data = array('_addItem');
		foreach (array('_orderId', 'sku', 'name', 'category', 'price', 'quantity') as $name)
			$data[] = $this->{$name};

		return \CJavaScript::encode($data);
	}

	/**
	 * Sets the order ID for the item
	 * @param string $orderId the order ID
	 */
	public function setOrderId($orderId)
	{
		$this->_orderId = $orderId;
	}

}
