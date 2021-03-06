<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
* A plugin for searching DSpace items.
 *
 * @package		JSolr.Plugin
 * @subpackage	Search
 * @copyright	Copyright (C) 2012-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolr Search DSpace plugin for Joomla!.

   The JSolr Search JSpace plugin for Joomla! is free software: you can 
   redistribute it and/or modify it under the terms of the GNU General Public 
   License as published by the Free Software Foundation, either version 3 of 
   the License, or (at your option) any later version.

   The JSolr Search JSpace plugin for Joomla! is distributed in the hope 
   that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolr Search JSpace plugin for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<hayden@knowledgearc.com> 
 * 
 */

jimport('joomla.error.log');

jimport('jsolr.search.search');

class plgJSolrSearchDSpace extends JSolrSearchSearch 
{
	protected $context = 'dspace';

	public function __construct(&$subject, $config = array()) 
	{
		parent::__construct($subject, $config);
		
		$this->set('highlighting', array("title", "body", "author"));
		$this->set('operators', array('author_fc'=>'author', 'type_fc'=>'type'));
	}

	/**
	 * Add custom filters to the main query.
	 */
	public function onJSolrSearchFQAdd()
	{
		$array = array('-context:'.$this->get('context').'.bitstream');
		
		return $array;
	}

	public function onJSolrSearchURIGet($document)
	{
		if ($this->get('context').'.item' == $document->context) {
			require_once(JPATH_ROOT."/components/com_jspace/helpers/route.php");

			return JSpaceHelperRoute::getItemFullRoute($document->id);
		}
	
		return null;
	}
	
	public function onJSolrSearchRegisterPlugin()
	{
		return array(
			'name'=>$this->_name,
			'label'=>'PLG_JSOLRSEARCH_'.JString::strtoupper($this->_name).'_LABEL',
			'context'=>$this->get('context').'.*'
		);
	}
}