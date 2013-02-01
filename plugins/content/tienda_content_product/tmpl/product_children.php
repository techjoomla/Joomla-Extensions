<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$items = @$vars->items;
$form = @$this->form;
?>
<?php if($items):?>
<form action="<?php echo JRoute::_( 'index.php?option=com_tienda&controller=products&view=products&id="'.$vars->product_id ); ?>" method="post" class="adminform" name="adminFormChildren" enctype="multipart/form-data" >
    
    <div class="reset"></div>

    <div id="product_children">
        <div id="product_children_header" class="tienda_header">
            <span><?php echo JText::_("Select the Items to Add to Your Cart"); ?></span>
        </div>
        
        <table class="adminlist">
        <thead>
        <tr>
            <th style="text-align: left;">
                <?php echo JText::_("Product Name"); ?>
            </th>
            <th style="text-align: left;">
                <?php echo JText::_("SKU"); ?>
            </th>
            <th style="text-align: center;">
                <?php echo JText::_("Price"); ?>
            </th>
            <th style="text-align: center;">
                <?php echo JText::_("Quantity"); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $k = 0;         
        foreach ($items as $item): ?>
        <tr>
            <td style="text-align: left;">
                <?php echo $item->product_name; ?>
            </td>
            <td style="text-align: left;">
                <?php echo $item->product_sku; ?>
            </td>
            <td style="text-align: center;">
                <?php  echo TiendaHelperProduct::dispayPriceWithTax($item->product_price, $item->tax, $item->showtax); ?>
            </td>
            <td style="text-align: center;">
                <input type="text" name="quantities[<?php echo $item->product_id; ?>]" value="1" size="5" />
            </td>        
        </tr>
        <?php $k = 1 - $k; ?>           
        <?php endforeach; ?>
        </tbody>
        </table>
        
        <div class="reset"></div> 
        
        <div id="validationmessage_children"></div>
        
        <!-- Add to cart button ---> 
        <div id='add_to_cart_children' style="display: block; float: right;">
            <input type="hidden" name="product_id" value="<?php echo $vars->product_id; ?>" />
            <input type="hidden" name="filter_category" value="<?php echo $vars->filter_category; ?>" />
            <input type="hidden" id="task" name="task" value="" />
            <?php echo JHTML::_( 'form.token' ); ?>
            
            <?php $onclick = "tiendaFormValidation( '".JRoute::_( @$vars->validation )."', 'validationmessage_children', 'addchildrentocart', document.adminFormChildren );"; ?>
            
            <?php 
            if (empty($item->product_check_inventory) || (!empty($item->product_check_inventory) && empty($this->invalidQuantity)) ) :
                switch (TiendaConfig::getInstance()->get('cartbutton', 'image')) 
                {
                    case "button":
                        ?>
                        <input onclick="<?php echo $onclick; ?>" value="<?php echo JText::_('Add to Cart'); ?>" type="button" class="button" />
                        <?php
                        break;
                    case "image":
                    default:
                        ?> 
                        <img class='addcart' src='<?php echo Tienda::getUrl('images')."addcart.png"; ?>' alt='<?php echo JText::_('Add to Cart'); ?>' onclick="<?php echo $onclick; ?>" />
                        <?php
                        break;
                }
            endif; 
            ?>
        </div>
        
        <div class="reset"></div>
    </div>

<div class="reset"></div>

</form>
<?php endif;?>