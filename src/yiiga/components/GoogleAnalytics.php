<?php

/**
 * GoogleAnalytics file.
 * @author Sam Stenvall <sam@supportersplace.com>
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package yiiga.components
 */

namespace yiiga\components;
use \Yii as Yii;

/**
 * Application component for handling Google Analytics integration.
 */
class GoogleAnalytics extends \CApplicationComponent
{

	/**
	 * @var string google analytics account ID (tracking code)
	 */
	public $accountId;

	/**
	 * @var string currency for the e-commerce tracking
	 */
	public $currency;
	
	/**
	 * @var string the domain for GATC cookies
	 */
	public $cookieDomain;
	
	/**
	 * @var \yiiga\models\Transaction[] list of registered transactions
	 */
	private $_transactions = array();

	/**
	 * Registers the google-analytics tracking code. Call this method on every 
	 * page that needs tracking. All registered transactions will also be 
	 * registered.
	 */
	public function registerTracking()
	{
		if (!isset($this->accountId))
			return;

		$headScript = <<<EOD
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '{$this->accountId}']);
EOD;

		if(isset($this->cookieDomain))
			$headScript .= "\n_gaq.push(['_setDomainName', '{$this->cookieDomain}']);";
			
		$headScript .= "\n_gaq.push(['_trackPageview']);";
		$bodyEndScript = $this->getTransactionsJS();
		$bodyEndScript .= <<<EOD

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
EOD;

		Yii::app()->clientScript->registerScript(__CLASS__.'#tracking-head', $headScript, \CClientScript::POS_HEAD);
		Yii::app()->clientScript->registerScript(__CLASS__.'#tracking-body', $bodyEndScript, \CClientScript::POS_END);
	}

	/**
	 * Returns the JavaScript for the registered transactions.
	 * @return string the script.
	 */
	protected function getTransactionsJS()
	{
		ob_start();
		foreach ($this->_transactions as $transaction)
		{
			echo "_gaq.push({$transaction->toJS()});".PHP_EOL;
			echo $transaction->getItemsJS();
			echo "_gaq.push(['_trackTrans']);".PHP_EOL;
		}
		return ob_get_clean();
	}

	/**
	 * Registers a transaction.
	 * @param \yiiga\models\Transaction $transaction
	 */
	public function addTransaction($transaction)
	{
		$this->_transactions[] = $transaction;
	}

}
