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
 
 ?>
<html>
    <head>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.0.2/cosmo/bootstrap.min.css" />
        <style>
            #logo-ipay88, .centerme {
                text-align: center;
            }
            #logo-ipay88 img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            h2 {
                margin-top: 10px;
                margin-bottom: 30px;
            }
            .btn-lg {
                border-radius: 5px;
            }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div id="logo-ipay88">
                <?php if($status == '1'): ?>                
                <h2 class="text-success"><i class="fa fa-check-circle"></i> Payment Completed</h2>
                <?php else: ?>
                <h2 class="text-danger"><i class="fa fa-times-circle"></i> Payment Failed</h2>
                <?php endif; ?>
                <hr>
            </div>
            <form action="<?php echo $_SERVER['REQUEST_URI'] ?>&gotoorder=<?php echo Tools::getValue('orderid') ?>" method="POST" id="ipay88-form">
                <?php foreach($_POST as $name => $value): ?>
                <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />            
                <?php endforeach; ?>
                <div class="centerme">
                    <button type="submit" class="btn btn-info btn-lg">Click here to continue</button>
                </div>                
            </form>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#ipay88-form').submit();
                });
            </script>
        </div>        
    </body>
</html>