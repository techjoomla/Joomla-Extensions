<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

class modMystuffHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    function getStuff( $params )
    {
    		
    		$db =& JFactory::getDBO();
				$user =& JFactory::getUser(); 
				$userid = $user->get('id');
				
				$today_date = date('Y-m-d H:i:s');
				$query ="SELECT * FROM #__tienda_subscriptions WHERE expires_datetime > '".$today_date."'AND user_id=".$userid." AND subscription_enabled=1";
				$db->setQuery($query);
				$subscription = $db->loadObjectList();
				$categorylist = array();
				
				foreach($subscription as $subsc_product){
				
						$productid = $subsc_product->product_id;
						$query ="SELECT product_params FROM #__tienda_products WHERE product_id =".$productid;
						$db->setQuery($query);
						$prodparams = $db->LoadResult();
						$paramsmeters = new JParameter($prodparams);
						$course_catid = $paramsmeters->get('courseid');
						
						
						$query ="SELECT name FROM #__k2_categories WHERE id =".$course_catid;
						$db->setQuery($query);
						$catname = $db->LoadResult();
						
						$caturl = K2HelperRoute::getCategoryRoute($course_catid);
					
						$categorylist[] = '<a href="'.$caturl.'">'.$catname.'</a>';
						
				}
				
						
				if(!empty($categorylist)) {
					return $categorylist;
				}
					
        
    }
}
?>

