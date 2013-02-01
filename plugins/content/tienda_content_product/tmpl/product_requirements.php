<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$items = @$vars->items;
?>

    <div class="reset"></div>

    <div id="product_requirements">
        <div id="product_requirements_header" class="tienda_header">
            <span><?php echo JText::_("Required Items"); ?></span>
        </div>
       
        <div class="note">
        <?php echo JText::_( "Required Products Note" ); ?>
        </div>
        
        <?php
        $k = 0;         
        foreach ($items as $item): ?>
        <div class="productrelation">
            <div class="productrelation_item">
                <div class="productrelation_image">
                    <a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=products&task=view&id='.$item->product_id . '&Itemid=' . $item->itemid ); ?>">
                        <?php echo TiendaHelperProduct::getImage($item->product_id, 'id', $item->product_name, 'full', false, false, array( 'width'=>64 ) ); ?>
                    </a>
                </div>
                <div class="productrelation_name">
                    <a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=products&task=view&id='.$item->product_id . '&Itemid=' . $item->itemid ); ?>">
                        <?php echo $item->product_name; ?>
                    </a>
                </div>
                <div class="productrelation_price" style="vertical-align: middle;" >
                    <?php  echo TiendaHelperProduct::dispayPriceWithTax($item->product_price, $item->tax, $item->showtax); ?>
                </div>
            </div>
        </div>
        <?php $k = 1 - $k; ?>           
        <?php endforeach; ?>
        
        <div class="reset"></div> 
    </div>
