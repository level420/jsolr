<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
* A plugin for searching articles.
 *
 * @package		JSolr.Plugin
 * @subpackage	Search
 * @copyright	Copyright (C) 2012-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolr Search content plugin for Joomla!.

   The JSolr Search content plugin for Joomla! is free software: you can 
   redistribute it and/or modify it under the terms of the GNU General Public 
   License as published by the Free Software Foundation, either version 3 of 
   the License, or (at your option) any later version.

   The JSolr Search content plugin for Joomla! is distributed in the hope 
   that it will be useful, but WITHOUT ANY WARRANTY; without even the implied 
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolr Search content plugin for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<hayden@knowledgearc.com> 
 * 
 */

jimport('joomla.error.log');
jimport('joomla.database.table.category');
jimport('joomla.database.table.content');

jimport('jsolr.search.search');

class plgJSolrSearchContent extends JSolrSearchSearch
{
	protected $context = 'com_content.article';

	public function __construct(&$subject, $config = array()) 
	{
		parent::__construct($subject, $config);
		
		$this->set('highlighting', array("title", "title_*", "body_*", "metadescription_*", "category_*"));
	}
	
	public function onJSolrSearchURIGet($document)
	{
		if ($this->get('context') == $document->context) {
			require_once(JPATH_ROOT."/components/com_content/helpers/route.php");
			
			return ContentHelperRoute::getArticleRoute($document->id, $document->parent_id);
		}
		
		return null;
	}
}