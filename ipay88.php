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

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_'))
    exit;

class ipay88 extends PaymentModule {
    private $_html = '';
    private $_postErrors = array();
    public $display_status = '';

    public function __construct() {
        $this->name = 'ipay88';        
        $this->tab = 'payments_gateways';
        $this->version = '17.0.1';
		$this->author = 'Lim Eng Seong';   
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';        
        $config = Configuration::getMultiple(array('ipay88_merchantKey', 'ipay88_merchantCode'));
		
        if(isset($config['ipay88_merchantKey']))
            $this->ipay88_merchantKey = $config['ipay88_merchantKey'];
        if(isset($config['ipay88_merchantCode']))
            $this->ipay88_merchantCode = $config['ipay88_merchantCode'];
               
        $this->page = basename(__FILE__, '.php');
        $this->displayName = 'iPay88 Payment Gateway';
        $this->description = $this->l('Accept payments with iPay88');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

        if(!count(Currency::checkPaymentCurrencies($this->id)))
            $this->warning = $this->l('No currency set for this module');
        if(!isset($this->ipay88_merchantKey) || !isset($this->ipay88_merchantCode))
            $this->warning = $this->l('Your ipay88 account must be set correctly');
        
        parent::__construct();
    }        

    /**
     * Install the ipay88 module into prestashop
     * 
     * @return boolean
     */
    function install() {
        if (!parent::install() 
        	|| !$this->registerHook('paymentOptions') 
        	|| !$this->registerHook('paymentReturn') 
        	|| !$this->registerHook('header'))
            return false;
        else
        {
        return true;
        }
    }
    
    /**
     * Uninstall the ipay88 module from prestashop
     * 
     * @return boolean
     */
    function uninstall() {
        if (!Configuration::deleteByName('ipay88_merchantKey') 
        	|| !Configuration::deleteByName('ipay88_merchantCode') 
        	|| !parent::uninstall())
            return false;
        else
            return true;
    }

    /**
     * Validate the form submited by ipay88 configuration setting
     * 
     */
    private function _postValidation() {
        if (Tools::isSubmit('submitipay88')) {
            if (!Tools::getValue('merchantCode'))
                $this->_set_display_status('iPay88 Merchant Code is required', 2);  
            else if (!Tools::getValue('merchantKey'))
                $this->_set_display_status('iPay88 Merchant Key is required.', 2);  
        }
    }

    /**
     * Save/update the ipay88 configuration setting
     * 
     */
    private function _postProcess() {
        if (Tools::getIsset(Tools::getValue('submitipay88'))) {
            Configuration::updateValue('ipay88_merchantCode', Tools::getValue('merchantCode'));
            Configuration::updateValue('ipay88_merchantKey', Tools::getValue('merchantKey'));
        }
        $this->_set_display_status('Settings updated', 1);	
    }
    
    /**
     * Display notification after saving the ipay88 configuration setting
     * 
     */
    private function _displayipay88() {
        $this->_html .= '<img src="../modules/ipay88/views/img/ipay88.gif" style="float:left; margin-right:15px;"><b>'.$this->l('This module allows you to accept payments by iPay88.').'</b><br /><br />
        <br /><br /><br />';
    }
    
    /**
     * Display the form to provide the ipay88 configuration setting
     * 
     */
    private function _displayForm() {
        $this->_html .=
        '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
            <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Contact details').'</legend>
                <table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
                    <tr><td width="140" style="height: 35px;">'.$this->l('iPay88 Merchant Code').'</td><td><input type="text" name="merchantCode" value="'.htmlentities(Tools::getValue('merchantCode', $this->ipay88_merchantCode), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                    <tr><td width="140" style="height: 35px;">'.$this->l('iPay88 Merchant Key').'</td><td><input type="text" name="merchantKey" value="'.htmlentities(Tools::getValue('merchantKey', $this->ipay88_merchantKey), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                    <tr><td colspan="2" align="center"><br /><input class="button" name="submitipay88" value="'.$this->l('Update settings').'" type="submit" /></td></tr>
                    <tr><td colspan="2" align="center"><br />'.$this->_get_display_status().'</td></tr>
                </table>
            </fieldset>
        </form>';
    }

    private function _set_display_status($message, $type)
    {
        if($type=='1'){
            $this->display_status ='<span class="text-success">'.$message.'</span>';
        }
        elseif($type=='2'){
            $this->display_status ='<span class="text-error">'.$message.'</span>';
        }
        else{
            $this->display_status = '';
        }
    }

    /**
     * display custom status
     * 
     * 
     */
    private function _get_display_status(){

        return $this->display_status;
    }

    /**
     * Display the ipay88 configuration setting. <call private method>
     * 
     * @return string
     */
    public function getContent() {
        $this->_html = '<h2>'.$this->displayName.'</h2>';
        if (Tools::isSubmit('submitipay88')) {
            $this->_postValidation();
            if (!count($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors as $err)
                    $this->_html .= '<div class="alert error">' . $err . '</div>';
        }
        else
            $this->_html .= '<br />';

        $this->_displayipay88();
        $this->_displayForm();
        return $this->_html;
    }

    /**
     * Hook ipay88 stylesheet to prestashop header method
     * 
     * @global array $smarty
     * @global array $cookie
     * @param mixed $params
     */
    public function hookHeader($params) {
        $this->context->controller->addCSS(($this->_path) . 'view/css/ipay88.css', 'all');
    }
    
    /**
     * Hook the payment form to the prestashop Payment method. Display in payment method selection
     * 
     * @param array $params
     * @return string
     */
    public function hookPaymentOptions($params) {
        if (!$this->active)
            return;
        if (!$this->checkCurrency($params['cart']))
            return;		
			
        $address     = new Address(intval($params['cart']->id_address_invoice));
        $customer    = new Customer((int)$this->context->cart->id_customer);
        $merchantCode = Configuration::get('ipay88_merchantCode');
        $merchantKey     = Configuration::get('ipay88_merchantKey');
        
        $currency_obj = $this->context->currency;
        $currency_code = $currency_obj->iso_code;
        $orderid = (int)$this->context->cart->id;
        $amount = $this->context->cart->getOrderTotal(true, Cart::BOTH);
		$amount = number_format($amount,2,".","");
        $bill_name = $customer->firstname." ".$customer->lastname;
        $bill_email = $customer->email;
        
        $country_obj =  new Country(intval($address->id_country));
        $country = $country_obj->iso_code;
        $country_name_obj = $country_obj->name;
        $country_name =  $country_name_obj[1];
		
		$ipaySignature = '';
		$HashAmount = str_replace(".","",str_replace(",","",$amount));
		$str = sha1($merchantKey . $merchantCode . $orderid . $HashAmount . $currency_code);
		
		for ($i=0;$i<Tools::strlen($str);$i=$i+2)
		{
        $ipaySignature .= chr(hexdec(Tools::substr($str,$i,2)));
		}
        $sg = base64_encode($ipaySignature);		
		
		$responseurl = Tools::getShopDomainSsl(true).__PS_BASE_URI__.'index.php?fc=module&module=ipay88&controller=validation';
		
        $this->smarty->assign(array(
            'MerchantCode' 	=> $merchantCode,
			'PaymentId' 	=> '',
			'RefNo'			=> $orderid,
			'Amount'		=> $amount,
			'Currency' 		=> $currency_code,
            'ProdDesc' 		=> 'Payment for Order #'.$orderid.'',
            'UserName' 		=> $bill_name,
            'UserEmail' 	=> $bill_email,
            'UserContact' 	=> $address->phone,
			'Remark' 		=> '',
			'Lang' 			=> "UTF-8",
			'Signature' 	=> $sg,
            'ipay88Url'  	=> 'https://www.mobile88.com/epayment/entry.asp',
            'returnurl' 	=> $responseurl,
            'this_path' => $this->_path
        ));		
        return $this->display(__FILE__, 'ipay88.tpl');
    }

    /**
     * Hook the payment return to the prestashop payment return method
     * 
     * @param array $params
     * @return string
     */
    public function hookPaymentReturn($params) {
        if (!$this->active)
            return;

        $state = $params['objOrder']->getCurrentState();
        if ($state == Configuration::get('PS_OS_PAYMENT')) {
            $this->smarty->assign(array(
                'status' => '1',
                'id_order' => $params['objOrder']->id
            ));
            if(isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
                $this->smarty->assign('reference', $params['objOrder']->reference);
        }		
        else if ($state == Configuration::get('PS_OS_ERROR')) {
            $this->smarty->assign(array(
                'status' => '0',
                'id_order' => $params['objOrder']->id
            ));
            if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
                $this->smarty->assign('reference', $params['objOrder']->reference);
        }
        else
            $this->smarty->assign('status', 'other');
			
        return $this->display(__FILE__, 'payment_return.tpl');
    }

    /**
     * Check the currency
     * 
     * @param object $cart
     * @return boolean
     */
    public function checkCurrency($cart) {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module))
            foreach ($currencies_module as $currency_module)
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
        return false;
    }	
}

?>
