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

{if $status == '1'}
<p>
    <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/ok.png" align="left">
    <strong>{l s='Your order on %s is complete.' sprintf=$shop_name mod='ipay88'}</strong>
    <br />
    <br />
    <strong>{l s='Your order will be sent soon.' mod='ipay88'}</strong>
    <br />
    <br />
    {l s='You can view your order history by following this link:' mod='ipay88'}
    <a href="{$link->getPageLink('history', true)}">{l s='Order History' mod='ipay88'}</a>
    <br />
    <br />
    {l s='For any questions or for further information, please contact our' mod='ipay88'} 
    <a href="{$link->getPageLink('contact', true)}">{l s='customer support' mod='ipay88'}</a>.
    <br />
    <br />
    <strong>{l s='Thank you!' mod='ipay88'}</strong>
</p>
{else}
<p class="warning">
    <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/not_ok.png" align="left">{l s='We noticed a problem with your order. Please, contact us as soon as possible' mod='ipay88'}.
    <br />
    <br />
    {l s='Your order will not be shipped until the issue is addressed' mod='ipay88'} 
    <br />
    <br />
</p>
{/if}
