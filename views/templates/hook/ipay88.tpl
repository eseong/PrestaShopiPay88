{*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<p class="payment_module">
    <a href="javascript:document.forms['ipay88_form'].submit();" title="{l s='Pay with iPay88 Payment Gateway' mod='ipay88'}">
        <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/ipay88.gif" alt="{l s='Pay with iPay88 Payment Gateway' mod='ipay88'}"/>
        {l s='Pay with iPay88 Payment Gateway' mod='ipay88'}
    </a>
</p>

<form action="{$ipay88Url}" method="post" id="ipay88_form">
	<input type="hidden" name="MerchantCode" value="{$MerchantCode}" />
	<input type="hidden" name="PaymentId" value="" />
	<input type="hidden" name="RefNo" value="{$RefNo}" />
	<input type="hidden" name="Amount" value="{$Amount}" />
	<input type="hidden" name="Currency" value="{$Currency}" />
	<input type="hidden" name="ProdDesc" value="{$ProdDesc}" />
	<input type="hidden" name="UserName" value="{$UserName}" />
	<input type="hidden" name="UserEmail" value="{$UserEmail}" />
	<input type="hidden" name="UserContact" value="{$UserContact}" />
	<input type="hidden" name="Remark" value="{$Remark}" />
	<input type="hidden" name="Lang" value="UTF-8" />
	<input type="hidden" name="ResponseURL" value="{$returnurl}" />	
	<input type="hidden" name="Signature" value="{$Signature}" />
</form>
