<?php
/**
 * A helper that provides assistance with permissions adn submenus for JSolrIndex.
 * 
 * @package		JSolr.Index
 * @copyright	Copyright (C) 2012-2013 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolrIndex component for Joomla!.

   The JSolrIndex component for Joomla! is free software: you can redistribute it 
   and/or modify it under the terms of the GNU General Public License as 
   published by the Free Software Foundation, either version 3 of the License, 
   or (at your option) any later version.

   The JSolrIndex component for Joomla! is distributed in the hope that it will be 
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolrIndex component for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<haydenyoung@knowledgearc.com> 
 * 
 */

defined('_JEXEC') or die('Restricted access');

class JSolrIndexHelper
{
	public static $extension = 'com_jsolrindex';

	/**
	 * Configure the Linkbar.
	 *
	 * @param string $vName The name of the active view.
	 *
	 * @return void
	 */
	public static function addSubmenu($vName)
	{

	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The community ID.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject();
		
		$assetName = 'COM_JSOLRINDEX';
		
		$actions = array(
			'core.admin', 
			'core.manage', 
			'core.create', 
			'core.edit', 
			'core.edit.own', 
			'core.edit.state', 
			'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}