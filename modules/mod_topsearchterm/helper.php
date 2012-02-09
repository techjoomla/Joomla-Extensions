<?php

defined('_JEXEC') or die('Restricted access');

class modTopsearchHelper
{
	
	function loadwords(&$params)
	{
		$limit = $params->get('limit');
		$db = JFactory::getDBO();
		$query = "SELECT search_term FROM #__core_log_searches ORDER BY hits DESC LIMIT ".$limit;
		$db->setQuery($query);
		$getwords = $db->loadObjectList();
		return $getwords;
	}
		
}
?>
