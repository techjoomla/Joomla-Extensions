<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
if(empty($mystuff))
{
	$link = "<a href=".JRoute::_('index.php?option=com_k2&view=itemlist&layout=category&task=category&id=27&Itemid=55').">".JText::_('CLICK_HERE')."</a>";
	
	echo str_replace('{link}',$link,JText::_('NOTHING_BOUGHT'));
	return;
}
	
	for($i=0;$i<count($mystuff);$i++){
	
			echo '<p style="font-weight:bold;">'.$mystuff[$i].'</p>';
	}

?>

