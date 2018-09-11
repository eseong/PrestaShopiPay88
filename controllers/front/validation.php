<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2018 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class IPay88ValidationModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;
    
    /**
     *
     *
     * @see FrontController::postProcess()
     */
    
    public function postProcess()
    {
    
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
        {
            Tools::redirect('index.php?controller=order&step=1');
	}
	    
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if($module['name'] == 'ipay88')
            {
                $authorized = true;
                break;
	    }
	}
	    if(!$authorized)
	    {
		    die($this->module->l('This payment method is not available.', 'validation'));
	    }
	    $customer = new Customer($cart->id_customer);
	    if (!Validate::isLoadedObject($customer))
	    {
		    Tools::redirect('index.php?controller=order&step=1');
	    }
	    
	    $merchantCode	= Tools::getValue('MerchantCode');
	    $paymentId 		= Tools::getValue('PaymentId');
	    $amount 		= Tools::getValue('Amount');
	    $orderid 		= Tools::getValue('RefNo');
	    $tranID 		= Tools::getValue('TransId');
	    $status 		= Tools::getValue('Status');
	    $currency 		= Tools::getValue('Currency');
	    $errdesc 		= Tools::getValue('ErrDesc');
	    $ipay88resqsig 	= Tools::getValue('Signature');
	    $merchantKey 	= Configuration::get('ipay88_merchantKey');
	    $cartAmount = str_replace(",","",$amount);
	    $ipaySignature = '';
	    $HashAmount = str_replace(".","",str_replace(",","",$amount));
	    $str = sha1($merchantKey . $merchantCode . $paymentId . $orderid . $HashAmount . $currency . $status);
	    
	    for ($i=0;$i<Tools::strlen($str);$i=$i+2)
	    {
		    $ipaySignature .= chr(hexdec(Tools::substr($str,$i,2)));
	    }
	    
	    $sg = base64_encode($ipaySignature);
	    $currency = $this->context->currency;
	    $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
	    
	    if(!empty(Tools::GET['gotoorder']))
	    {
		    echo include_once 'modules/ipay88/views/templates/custom.php';
		    die();
	    }
	    
	    if($status == "1") {
		    if ($ipay88resqsig != $sg)
		    {
			    $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $cartAmount, $this->module->displayName, "ErrDesc: " . $errdesc . "\niPay88 Transaction ID: " . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
				Tools::redirect('index.php?controller=history');
		    }
		    else
		    {
			    $this->module->validateOrder($orderid, Configuration::get('PS_OS_PAYMENT'), $cartAmount, $this->module->displayName, "iPay88 Transaction ID: " . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
				Tools::redirect('index.php?controller=order-confirmation&id_cart='.$orderid.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
		    }
	    }
	    else {
		    $this->module->validateOrder($orderid, Configuration::get('PS_OS_ERROR'), $cartAmount, $this->module->displayName, "ErrDesc: " . $errdesc . "\niPay88 Transaction ID: " . $tranID, NULL, (int)$currency->id, false, $customer->secure_key);
	    }
	    Tools::redirect('index.php?controller=history');
    }
}

?>
