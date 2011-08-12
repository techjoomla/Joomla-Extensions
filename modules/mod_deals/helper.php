<?php
/**
 * @version: $Id: helper.php 1413 2011-05-26 10:55:39Z Radek Suski $
 * @package: SobiPro Entries Module Application 
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-05-26 12:55:39 +0200 (Do, 26 Mai 2011) $
 * $Revision: 1413 $
 * $Author: Radek Suski $
 */

defined('_JEXEC') || die( 'Direct Access to this location is not allowed.' );
//SPLoader::loadController( 'section' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class modDealsHelper
{
	function ListEntries( $params )
	{

		$database = JFactory::getDBO();
		$section = $params->get( 'sid' );
		$limit= $params->get('entriesLimit');
		$deal_value = $params->get( 'deal_value' );
		$stitleid = $params->get( 'title' );
		$simageid = $params->get( 'image' );
		$order = $params->get( 'spOrder' );
		
		$query = "SELECT id FROM #__menu WHERE title = 'COM_SOBIPRO'";
		$database->setQuery($query);	
		$Itemid = $database->loadResult();
		$query = "SELECT * FROM #__sobipro_field_data WHERE fid  = $simageid AND section = $section LIMIT $limit";
		$database->setQuery($query);
		$result = $database->loadObjectList();
		$date = date('Y-m-d');
		foreach($result as $res) {
			
			$query = "SELECT a.fid,a.sid,a.baseData,b.parent
					  FROM #__sobipro_field_data AS a ,#__sobipro_object AS b 
					  WHERE a.fid IN ($stitleid,$deal_value) 
					  AND a.sid = $res->sid 
					  AND a.sid = b.id
					  AND DATEDIFF(b.validUntil,'{$date}') >=0
					  ORDER BY b.$order";
			$database->setQuery($query);
			$slink = $database->loadObjectList();
			
			//echo $slink->fid;
			if($slink[0]->fid == $stitleid)
			{
				$title = $slink[0]->baseData;
				$slink[0]->baseData = str_replace('%','',$slink[0]->baseData);
				$link = 'index.php?option=com_sobipro&pid='.$slink[0]->parent.'&sid='.$slink[0]->sid.':'.str_replace(' ','-',$slink[0]->baseData).'&Itemid='.$Itemid;
			}
			if($slink[1]->fid == $deal_value)
			{
				$dvalue = $slink[1]->baseData;
			}
			//$target = modDJImageSliderHelper::getSlideTarget($params->get('link'));
			$slide_image = unserialize(base64_decode($res->baseData));
			$slides[] = (object) array('title'=>$title,'image'=>$slide_image['image'], 'link'=>$link, 'alt'=>$image,'dvalue'=>$dvalue);
			
			
			//print_r($slides);
		}
		return $slides;
			
	}

}
?>
