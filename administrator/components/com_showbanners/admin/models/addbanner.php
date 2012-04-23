<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );
jimport( 'joomla.database.table.user' );



class showbannersModelAddbanner extends JModel
{
	var $_total = null;
	var $_pagination = null;
	function __construct()
  {
	parent::__construct();
  }

     function getdata()
     { 
		 $data=JRequest::get('post');
		 $id=JRequest::getVar('cid');
		
		 if(empty($data) && $id[0]!="")
		 {
			 $data['cid'][0]=$id;
		 }
		
		if(!empty($data['cid'])) {
		 $db=JFactory::getDBO();
		 $query="SELECT * FROM #__banners_details WHERE banner_id=".$data['cid'][0];
		 $db->setQuery($query);
		 $details=$db->loadObjectList();
		 
		 $query1="SELECT banner_iplower,banner_iphigher FROM #__banners_iprange WHERE banner_id=".$data['cid'][0];
		 $db->setQuery($query1);
		 $ips=$db->loadObjectList();
	
		 foreach($ips AS $d)
		 {
			 $iplower = long2ip($d->banner_iplower);
			 $iphigher = long2ip($d->banner_iphigher);
			 $mystring[]=$iplower."-".$iphigher;
		 }
		 $mynewstring=implode("\n",$mystring);
		 foreach($details as $dt)
		 {
			 $dt->iprange= $mynewstring;
		 }
		
		 $this->_data=$details;
	    }
		 return $this->_data;
	}
	
    function store()
    {
		$data = array_map('htmlentities',$_POST);
		$datam =htmlentities($data['bannercode']);
		$data2=htmlspecialchars_decode($datam);
		$test=htmlspecialchars_decode($data['bannercode']);
		$a = htmlentities($data2);
		$b = html_entity_decode($a);
		$pattern='\"';
		$replace='"';
		$names=str_replace('\\',' ',$b);
		$b = html_entity_decode($names);
		$ip = inet_pton($data['ipaddr']);
		
		$db=JFactory::getDBO();
		if($data['banner_id']=="")
		{ 
			$res = new stdClass();
			$res->banner_id="";
			$res->banner_name=$data['bannername'];
			$res->banner_code=html_entity_decode($names);
			$res->banner_ips=$data['ipaddr'];
			
			if (!$db->insertObject( '#__banners_details', $res, 'banner_id' )) {
					echo $db->stderr();
					return false;
			}
	   
		$query="SELECT banner_id FROM #__banners_details ORDER BY banner_id dESC";
		$db->setQuery($query);
		$bannerid=$db->loadResult();
		
		$range=$data['iprange'];
		$rangearr=explode("\n",$range);
		
		for($i=0;$i<count($rangearr);$i++)
		{
			$newarr[$i]=explode('-',$rangearr[$i]);
		}
		
		for($j=0;$j<count($newarr);$j++)
		{
			
			$high=trim($newarr[$j][1]);
		    $iplower = ip2long($newarr[$j][0]);
			$iphigher = ip2long($high);//die;
			
			$result=new stdClass();
			$result->banner_id=$bannerid;
			
			$result->banner_iplower=$iplower;
			$result->banner_iphigher=$iphigher;
			
			if (!$db->insertObject( '#__banners_iprange', $result, 'banner_id' )) {
			    echo $db->stderr();
				return false;
		}
		}
		} else
	   if($data['banner_id']!="")
	   {
		 
		   $res = new stdClass();
			$res->banner_id=$data['banner_id'];
			$res->banner_name=$data['bannername'];
			$res->banner_code=html_entity_decode($names);
			$res->banner_ips=$data['ipaddr'];
			
			if (!$db->updateObject( '#__banners_details', $res, 'banner_id' )) {
					echo $db->stderr();
					return false;
			}
			$range=$data['iprange'];
		$rangearr=explode("\n",$range);
		
		for($i=0;$i<count($rangearr);$i++)
		{
			$newarr[$i]=explode('-',$rangearr[$i]);
		}
		
			$query="DELETE FROM #__banners_iprange WHERE banner_id=".$data['banner_id'];
			$db->setQuery($query);
			$db->query();
		//die;
		for($j=0;$j<count($newarr);$j++)
		{
			$high=trim($newarr[$j][1]);
			$iplower = ip2long($newarr[$j][0]);
			$iphigher = ip2long($high);
			$result=new stdClass();
			$result->banner_id=$data['banner_id'];
			$result->banner_iplower=$iplower;
			$result->banner_iphigher=$iphigher;
			//print_r($result);die;
			if (!$db->insertObject( '#__banners_iprange', $result, 'banner_id' )) {
			    echo $db->stderr();
				return false;
		   }
	    }
	  }
	}
}
 
