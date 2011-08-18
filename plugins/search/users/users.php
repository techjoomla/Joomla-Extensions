<?php
/**
 * @copyright (C) 2010 StyleWare. All rights reserved!
 * @license GNU/GPL V2
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JPlugin::loadLanguage( 'plg_search_users', 'administrator' );

class plgSearchUsers extends JPlugin
{

	function plgSearchUsers( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}


	function onContentSearchAreas()
	{
		static $areas = array(
			'users' => 'Users'
		);
		return $areas;
	}
	
	/** 1.6 **/
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		return $this->onSearch( $text, $phrase, $ordering, $areas );
	}

/**
* Users Search method
*/
	function onSearch( $text, $phrase='', $ordering='', $areas=null )
	{
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();

		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( plgCommunitySearchUsersAreas() ) )) {
				return array();
			}
		}

		// load plugin params info
	 	$plugin			=& JPluginHelper::getPlugin('search', 'users');
	 	$pluginParams	= new JParameter( $plugin->params );

		$limit			= $pluginParams->def( 'search_limit', 50 );
		$allowRealName	= $pluginParams->def( 'enable_realnamme', 1 );
		$hideSuperadmin	= $pluginParams->def( 'hide_superadmin', 1 );

		$section = JText::_( 'Users' );

		switch ( $ordering )
		{
			case 'alpha':
				$order = 'j.name ASC';
				break;

			case 'category':
			case 'popular':
			case 'newest':
			case 'oldest':
			default:
				$order = 'j.name DESC';
		}

		$text = trim( $text );
		if ($text == '') {
			return array();
		}

		switch ($phrase)
		{
			case 'exact':
				$text		= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
				$wheres2 	= array();
				if ($allowRealName) $wheres2[] 	= 'j.name LIKE '.$text;
				$wheres2[] 	= 'j.username LIKE '.$text;
				$wheres2[] 	= 'j.email LIKE '.$text;
				$wheres2[] 	= 'c.status LIKE '.$text;
				$where 		= '(' . implode( ') OR (', $wheres2 ) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					$wheres2 	= array();
					if ($allowRealName) $wheres2[] 	= 'j.name LIKE '.$word;
					$wheres2[] 	= 'j.username LIKE '.$word;
					$wheres2[] 	= 'j.email LIKE '.$word;
					$wheres2[] 	= 'c.status LIKE '.$word;
					$wheres[] 	= implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}

		// We should hide the super admin level user by default
		// since in most cases they are not really the end user
		$usersFilter	= ($hideSuperadmin) ? ' AND j.gid <> 25' : '';

		$query	= 'SELECT j.id AS id, j.name AS title, c.status AS text,'
				. ' j.registerDate AS created, "'.JText::_( 'Users' ).'" AS section, "2" AS browsernav'
				. ' FROM ' . $db->nameQuote('#__community_users') . ' AS c'
				. ' INNER JOIN ' . $db->nameQuote('#__users') . ' AS j ON j.id = c.userid'
				. ' WHERE ' . $where
				. ' AND j.block = ' . $db->quote(0)
				. $usersFilter
				. ' GROUP BY j.id'
				. ' ORDER BY ' . $order;
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();

		if ($rows)
		{
			$comunityLib	= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';
			include_once($comunityLib);
	
			foreach($rows as $key => $row) {
				$rows[$key]->href		= CRoute::_('index.php?option=com_community&view=profile&userid='.$row->id);
			}
		}

		return $rows;
	}
}	
