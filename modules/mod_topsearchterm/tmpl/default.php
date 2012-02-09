<?php

defined('_JEXEC') or die('Restricted access');
	
foreach($words as $word)
{
	$link = JRoute::_("index.php?searchword=".$word->search_term."&searchphrase=all&Itemid=119&option=com_search");
	echo '<a href='.$link.'>'.$word->search_term.'</a><br />';
}
?>

