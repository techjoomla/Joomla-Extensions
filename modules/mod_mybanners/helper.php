<?php
/**
 * @version		$Id: helper.php 21421 2011-06-03 07:21:02Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.application' );
jimport('joomla.user.authentication');
jimport( 'joomla.application.module.helper' );

class modMybannersHelper
{
	function getBanners($params)
	{
		$db = JFactory::getDBO();
		$ips = modMybannersHelper::getIP();
		
		if (!$ips) { return false; }
		
		$ip=ip2long($ips);
		//echo $ip;die;
		$query="SELECT banner_id FROM #__banners_details WHERE banner_ips like '%".$ips."%'";
		$db->setQuery($query);
		$bannerids=$db->loadResult();
		
		$query1="SELECT banner_id FROM #__banners_iprange 
		        WHERE banner_iplower <='".$ip."' 
		        AND banner_iphigher >= '".$ip."'";
		$db->setQuery($query1);
		$banneridsrange=$db->loadResult();
		
		if ($bannerids) { $array[] = $bannerids; }
		if ($banneridsrange) { $array[] = $banneridsrange; }
		
		/*
		$array=array();
		foreach($bannerids AS $bann)
		{
			array_push($array,$bann->banner_id);
		}
		foreach($banneridsrange AS $bann)
		{
			array_push($array,$bann->banner_id);
		}*/
		
		$bannerid=implode(',',$array);
				
		$query3="SELECT banner_id,banner_code 
		         FROM #__banners_details 
		         WHERE banner_id IN (".$bannerid.") ORDER BY RAND()";
		$db->setQuery($query3);
		$bannercodes=$db->loadObjectList();

/*		if(!empty($bannercodes)) {
		array_rand($bannercodes);
	}*/
		
		//print_r($bannercodes);die;
		//print_r($banneridsrange); die;
		return $bannercodes;
		
	}
	
	public function getIP() {
		if ( isset($_SERVER["REMOTE_ADDR"]) )    {
			return $_SERVER["REMOTE_ADDR"];
		} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    {
			return $_SERVER["HTTP_CLIENT_IP"];
		} 
	}
}
	
