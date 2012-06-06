<?php

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
		$array=array();
				
		if (!$ips) { return false; }
		
		$ip=ip2long($ips);

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
		
		$bannerid=implode(',',$array);
		$query3 = "SELECT banner_id,banner_code 
					FROM #__banners_details 
					WHERE banner_id IN (".$bannerid.") ORDER BY RAND()";
		$db->setQuery($query3);
		$bannercodes=$db->loadObjectList();

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
	
