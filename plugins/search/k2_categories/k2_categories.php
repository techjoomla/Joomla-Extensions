<?php
/**
 * @version		$Id: k2.php 548 2010-08-30 15:39:07Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$mainframe->registerEvent('onSearch', 'plgSearchK2Categories');

/*
JPlugin::loadLanguage('plg_search_k2', JPATH_ADMINISTRATOR);

function & plgSearchItemsAreas() {
	static $areas = array('k2'=>'K2 Items');
	return $areas;
}
*/

function plgSearchK2Categories($text, $phrase = '', $ordering = '', $areas = null) {

	$mainframe = &JFactory::getApplication();

	$db = &JFactory::getDBO();
	$jnow = &JFactory::getDate();
	$now = $jnow->toMySQL();
	$nullDate = $db->getNullDate();
	$user = &JFactory::getUser();
	$access = $user->get('aid');
	$tagIDs = array();
	$itemIDs = array();

	require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php');
	require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

	$searchText = $text;
	if (is_array($areas)) {
		if (!array_intersect($areas, array_keys(plgSearchItemsAreas()))) {
			return array();
		}
	}

	$plugin = &JPluginHelper::getPlugin('search', 'k2_categories');
	$pluginParams = new JParameter($plugin->params);

	$limit = $pluginParams->def('search_limit', 50);

	$text = JString::trim($text);
	if ($text == '') {
		return array();
	}

	$rows = array();

	if ($limit> 0){

		$text = trim( $text );
		if ( $text == '' ) {
			return array();
		}

		$text	= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
		$query = "
		SELECT ca.id, ca.name AS title, ca.description as text, cb.name as section
    	FROM #__k2_categories AS ca, #__k2_categories AS cb, #__k2_categories AS cc
    	WHERE ca.parent=cb.id AND cb.parent=cc.id AND ca.published = 1 AND cb.published = 1 AND cc.published = 1
    	AND ca.access <= {$access} AND ca.name LIKE {$text}";

		switch ($ordering) {

			case 'alpha':
				$query.= 'ORDER BY ca.name ASC';
				break;

			case 'category':
				$query.= 'ORDER BY cb.name ASC';
				break;

			case 'newest':
			case 'oldest':
			case 'popular':
			default:
				$query.= 'ORDER BY ca.ordering ASC';
				break;
		}

		$db->setQuery($query, 0, $limit);
		$list = $db->loadObjectList();



		foreach ($list as $key=>$item) {
			$list[$key]->href = JRoute::_(K2HelperRoute::getCategoryRoute($item->id));

		}


	}

	return $list;
}
