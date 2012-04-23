<?php
// no direct access
defined( '_JEXEC' ) or die( ';)' );

jimport( 'joomla.application.component.model' );
jimport( 'joomla.database.table.user' );



class showbannersModelMybanners extends JModel
{
	var $_total = null;
	var $_pagination = null;
	
	 
    var $_search = null;
	var $_query = null;
	function __construct()
	{
		parent::__construct();
		$mainframe 	=& JFactory::getApplication();
		//get the number of events from database
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		//$limit       	= $mainframe->getUserStateFromRequest('com_jmailalerts.manageuser.limit', 'limit', $mainframe->getCfg('list_limit') , 'int');
		$limitstart		= JRequest::getVar('limitstart', 0, '', 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);	
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
	}
	function getData()
    { 
		$user =& JFactory::getUser();
		$db=JFactory::getDBO();
		$pagination =& $this->getPagination();
		if (empty($this->_data))
		{ //die;
 	    $query = $this->_buildQuery();
 	    
 	    $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));	
		}
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadobjectlist();
		
		return $this->_data;
	    
	}
	function _buildQuery()
	{
	 
		if(!$this->_query) 
		{
			//$where = array();
			$where		= $this->_buildQueryWhere();	
			$db			=& $this->getDBO();	
		    $this->_query = "SELECT bn.* FROM #__banners_details as bn"; 
		    $this->_query	.= $where;
        }         
        return $this->_query;
	}
	//function for where clouse
	function _buildQueryWhere()
	{
		$mainframe			=& JFactory::getApplication();
		$db					=& $this->getDBO();
		$search 			= $this->getSearch();
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
		$where = array();
		if($search !='' )
		{
			$where[] = " bn.banner_name LIKE '%{$search}%' ";
		}
		$where 		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' ;

		return $where;
	}
	//function for search filter
function getSearch()
	{
		if (!$this->_search)
		 {
			global $mainframe, $option;
			$mainframe = JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
			
			$this->_search = JString::strtolower($search);
		}
		return $this->_search;
	}
	
	
	function getTotal()
	{
		if (!$this->_total)
	    {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	function &getPagination()
	 {
		if(!$this->_pagination)
		 {
			jimport('joomla.html.pagination');
			global $mainframe;
			$mainframe = JFactory::getApplication();
			$this->_pagination = new JPagination($this->getTotal(), JRequest::getVar('limitstart', 0), JRequest::getVar('limit', $mainframe->getCfg('list_limit')));
	 }

		return $this->_pagination;
	}	
	function edit()
	{
		//die;
	}
	function delete()
	{ 
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$result = false;
        $db=JFactory::getDBO();
        
		for($i=0;$i<count( $cids );$i++)
		{ 
			$query="DELETE FROM #__banners_iprange WHERE banner_id=".$cids[$i];
			$db->setQuery($query);
			$db->query();
			
			$this->_db->setQuery( "DELETE FROM #__banners_details WHERE banner_id = ".$cids[$i] );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	
}
 
