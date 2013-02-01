<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$downloadItems = @$vars->downloadItems;
$nondownloadItems = @$vars->nondownloadItems;
?>

    <div id="product_files">
        <div id="product_files_header" class="tienda_header">
            <span><?php echo JText::_("Files"); ?></span>
        </div>
       
        <?php
        $k = 0;         
        foreach ($downloadItems as $item): ?>
        <div class="productfile">
            <span class="productfile_image">
                <a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=products&task=downloadfile&format=raw&id='.$item->productfile_id."&product_id=".$vars->product_id); ?>">
                    <img src="<?php echo Tienda::getURL('images')."download.png"; ?>" alt="<?php echo JText::_('Download') ?>" style="height: 24px; padding: 5px; vertical-align: middle;" />
                </a>
            </span>            
            <span class="productfile_link" style="vertical-align: middle;" >
                <a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=products&task=downloadfile&format=raw&id='.$item->productfile_id."&product_id=".$vars->product_id); ?>"><?php echo $item->productfile_name; ?></a>
            </span>
        </div>
        <?php $k = 1 - $k; ?>           
        <?php endforeach; 
        
        foreach ($nondownloadItems as $item): ?>
        <div class="productfile">
            <span class="productfile_image">
                   <img src="<?php echo Tienda::getURL('images')."download.png"; ?>" alt="<?php echo JText::_('Download') ?>" style="height: 24px; padding: 5px; vertical-align: middle;" />
                           </span>            
            <span class="productfile_link" style="vertical-align: middle;" >
               <?php echo $item->productfile_name; ?>
            </span>
        </div>
        <?php $k = 1 - $k; ?>           
        <?php endforeach; ?> 
        
    </div>


