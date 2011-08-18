<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
JHTML::_('script', 'tienda_inventory_check.js', 'media/com_tienda/js/');
$item = @$row;

Tienda::load('TiendaHelperCategory', 'helpers.category');
Tienda::load('TiendaUrl', 'library.url');
?>  

<div id="tienda" class="products view">
    
    <div id="tienda_product">

        <?php if (!empty($onBeforeDisplayProduct)) : ?>
            <div id='onBeforeDisplayProduct_wrapper'>
            <?php echo $onBeforeDisplayProduct; ?>
            </div>
        <?php endif; ?>
    
        <div id='tienda_product_header'>
        <?php if(@$params['show_name'] == '1'): ?>
            <span class="product_name">
                <?php 
                	echo $item->product_name; 
                ?>
            </span>
        <?php endif; ?>
      
            <div class="product_numbers">
                <?php if (!empty($item->product_model) && @$params['show_model']) : ?>
                    <span class="model">
                        <span class="title"><?php echo JText::_('Model'); ?>:</span> 
                        <?php echo $item->product_model; ?>
                    </span>
                <?php endif; ?>
                
                <?php if (!empty($item->product_sku) && @$params['show_sku']) : ?>
                    <span class="sku">
                        <span class="title"><?php echo JText::_('SKU'); ?>:</span> 
                        <?php echo $item->product_sku; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if(@$params['show_image'] == '1'): ?>
        <div class="product_image">
            <?php echo TiendaUrl::popup( TiendaHelperProduct::getImage($item->product_id, '', '', 'full', true), TiendaHelperProduct::getImage($item->product_id), array('update' => false, 'img' => true)); ?>
            <div>
            <?php
                if (isset($item->product_full_image))
                {
                    echo TiendaUrl::popup( TiendaHelperProduct::getImage($item->product_id, '', '', 'full', true), "View Larger", array('update' => false, 'img' => true ));
                }
            ?>
            </div>
        </div>
        <?php endif;?>
        
        <?php if (TiendaConfig::getInstance()->get('shop_enabled', '1') && @$params['show_buy']) : ?>
            <div class="product_buy" id="product_buy">
                <?php if (!empty($product_buy)) { echo $product_buy; } ?>
            </div>
        <?php else: ?>
        
        <?php if(@$params['show_price'] == '1'): ?>
		    <!--base price-->
		    <div class="product_buy" id="product_buy">
		    <span id="product_price" class="product_price">
		        <?php            
		        // For UE States, we should let the admin choose to show (+19% vat) and (link to the shipping rates)
		        if (!empty($vars->show_tax))
		        {
		            if (!empty($vars->tax))
		            {
		                if ($vars->show_tax == '2')
		                {
		                    echo TiendaHelperBase::currency($item->price + $vars->tax);
		                }
		                    else
		                {
		                    echo TiendaHelperBase::currency($item->price);
		                    echo sprintf( JText::_('INCLUDE_TAX'), TiendaHelperBase::currency($vars->tax));
		                }
		            }
		                else
		            {
		                echo TiendaHelperBase::currency($item->price);
		            }
		        }
		            else
		        {
		            echo TiendaHelperBase::currency($item->price);
		        }
		        
		        if (TiendaConfig::getInstance()->get( 'display_prices_with_shipping') && !empty($item->product_ships))
		        {
		            echo '<br /><a href="'.$vars->shipping_cost_link.'" target="_blank">'.sprintf( JText::_('LINK_TO_SHIPPING_COST'), $vars->shipping_cost_link).'</a>' ;
		        }
		        ?>
		    </span>
		    </div>
		    <?php endif; ?>
		    <?php endif; ?>
        
        <?php // display this product's group ?>
        <?php if(@$params['show_children'] == '1'): ?>
        <?php echo $product_children; ?>
        <?php endif;?>
                
        <?php if ($product_description && $params['show_description'] == '1') : ?>
            <div class="reset"></div>
            
            <div id="product_description">
                <?php if (TiendaConfig::getInstance()->get('display_product_description_header', '1')) : ?>
                    <div id="product_description_header" class="tienda_header">
                        <span><?php echo JText::_("Description"); ?></span>
                    </div>
                <?php endif; ?>
                <?php echo $product_description; ?>
            </div>
        <?php endif; ?>
        
        <?php if(@$params['show_image'] == '1'): ?>
        <?php // display the gallery images associated with this product if there is one ?>
        <?php $path = TiendaHelperProduct::getGalleryPath($item->product_id); ?>
        <?php $images = TiendaHelperProduct::getGalleryImages( $path, array( 'exclude'=>$item->product_full_image ) ); ?>
        <?php
        jimport('joomla.filesystem.folder');
        if (!empty($path) && !empty($images))
        {
            ?>
            
            <div class="reset"></div>
            <div class="product_gallery">
                <div id="product_gallery_header" class="tienda_header">
                    <span><?php echo JText::_("Images"); ?></span>
                </div>
                <?php            
                $uri = TiendaHelperProduct::getUriFromPath( $path );
                foreach ($images as $image)
                {
                    ?>
                    <div class="subcategory">
                        <div class="subcategory_thumb">
                            <?php echo TiendaUrl::popup( $uri.$image, '<img src="'.$uri."thumbs/".$image.'" />' , array('update' => false, 'img' => true)); ?>
                        </div>
                    </div>
                    <?php 
                } 
                ?>
                <div class="reset"></div>
            </div>
            <?php        		
        }
        ?>
        <?php endif; ?>
        <div class="reset"></div>

        <?php // display the files associated with this product ?>
        <?php if(@$params['show_files'] == '1'): ?>
        <?php echo $files; ?>
        <?php endif; ?>
        
        <?php // display the products required by this product ?>
        <?php if(@$params['show_requirements'] == '1'): ?>
        <?php echo $product_requirements; ?>
        <?php endif;?>

        <?php // display the products associated with this product ?>
        <?php if(@$params['show_relations'] == '1'): ?>
        <?php echo $product_relations; ?>
        <?php endif; ?>

        <?php if (!empty($onAfterDisplayProduct)) : ?>
            <div id='onAfterDisplayProduct_wrapper'>
            <?php echo $onAfterDisplayProduct; ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>
