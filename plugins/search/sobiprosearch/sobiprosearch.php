<?php
/**
 * @version		$Id: sections.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchSobiprosearch' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchSobiprosearchAreas' );

JPlugin::loadLanguage( 'plg_search_sections' );

/**
 * @return array An array of search areas
 */
function &plgSearchSobiprosearchAreas() {
	static $areas = array(
		'sobiprosearch' => 'soboprosearch'
	);
	return $areas;
}

/**
* Sections Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if restricted to areas, null if search all
*/
function plgSearchSobiprosearch( $text, $phrase='', $ordering='', $areas=null )
{
	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();


	// load plugin params info
 	$plugin =& JPluginHelper::getPlugin('search', 'sobiprosearch');
 	$pluginParams = new JParameter( $plugin->params );
 	
 	$limit = $pluginParams->def( 'search_limit', 50 );
 	$fids = $pluginParams->def( 'search_field');
 	$menu_itemid			= $pluginParams->def('menu_itemid',		null);
	$searchText = $text;
	
	$text = trim($text);
		if ($text == '') {
			return; //array();
		}
	$menu_itemid = trim($menu_itemid);
		if (is_numeric($menu_itemid)) {
			$menu_itemid = '&Itemid=' . $menu_itemid;
		}
		else {
			$menu_itemid = '';
		}
		
	switch ($ordering) {
			case 'alpha':
				$order = 'a.baseData ASC,relevance';
				break;

			case 'category':
				$order = 'c.title ASC, a.baseData ASC,relevance';
				break;

			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'a.basedata DESC,relevance';
		}
		$text = $db->Quote('"'.$db->getEscaped($text, true).'"',false);
		$query = "SELECT DISTINCT a.sid ,a.baseData as title,b.parent,c.sValue AS section,c.sValue, 
					     MATCH(a.baseData) AGAINST ($text IN BOOLEAN MODE ) AS relevance 
						 FROM jos_sobipro_field_data AS a, jos_sobipro_object AS b,jos_sobipro_language AS c 
						 WHERE MATCH(a.baseData) AGAINST ($text IN BOOLEAN MODE ) 
						 AND a.fid IN ($fids) 
						 AND a.sid = b.id 
						 AND c.sKey = 'name' 
						 AND b.parent = c.id 
						 AND c.oType = 'category' 
						 HAVING relevance > 0.2 
						 ORDER BY $order 
						 LIMIT $limit ";
				   
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		foreach($result as $res)
		{	
			$query = "SELECT baseData AS title FROM #__sobipro_field_data WHERE sid = $res->sid AND fid IN ($fids)";
			$db->setQuery($query);
			$lresult = $db->loadObjectList();
			
		}
		
		if ($rows) {
			foreach($rows as $key => $row) {
			if($row->pid == '60')
			{
				$rows[$key]->href = 'index.php?option=com_sobipro&pid=' .$row->parent. '&sid='.$row->sid.'&catid='.$menu_itemid;
			}else
			{
				$rows[$key]->href = 'index.php?option=com_sobipro&pid=' .$row->parent. '&sid='.$row->sid.':'.$row->title.'&catid='.$menu_itemid;
			}
			}
		}
				
	return $rows;
}
