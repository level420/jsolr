<?php
/**
 * Provides a selection search tool for filtering results.
 * 
 * @package		JSolr
 * @subpackage	Form
 * @copyright	Copyright (C) 2013-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSpace component for Joomla!.

   The JSpace component for Joomla! is free software: you can redistribute it 
   and/or modify it under the terms of the GNU General Public License as 
   published by the Free Software Foundation, either version 3 of the License, 
   or (at your option) any later version.

   The JSpace component for Joomla! is distributed in the hope that it will be 
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSpace component for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Michał Kocztorz				<michalkocztorz@wijiti.com> 
 * Hayden Young 				<haydenyoung@knowledgearc.com>
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
jimport('jsolr.form.fields.filterable');

JFormHelper::loadFieldClass('list');

class JSolrFormFieldSearchTool extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var         string
	 * @since       1.6
	 */
	protected $type = 'JSolr.SearchTool';

	protected function getInput()
	{
		$encoded = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$label = JText::_($this->getSelectedLabel());
		$options = implode($this->getOptions());
		
		$html =
<<<HTML
<input 
	type="hidden" 
	name="$this->name" 
	id="$this->id" 
	value="$encoded"/>
<div class="jsolr-searchtool">
	<a 
		class="dropdown-toggle" 
		id="$this->name-selected" 
		role="button" 
		data-toggle="dropdown" 
		data-target="#" 
		data-original="$this->value">
		$label<b class="caret"></b>
		
		<ul 
			class="dropdown-menu" 
			role="menu" 
			aria-labelledby="$this->name">$options</ul>
</div>
HTML;

		return $html;
	}
	
	protected function getSelectedLabel() {
		$ret = "";
		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$selected = ((string) $option['value']) == $this->value;
			if( $selected ) {
				return trim((string) $option);
			}
		
		}
		
		return $ret;
	}
	
	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
	
		foreach ($this->element->children() as $option)
		{
	
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}
			
			$selected = ((string) $option['value']) == $this->value;
	
			// Create a new option object based on the <option /> element.
			$tmp = '<li role="presentation" class="' . ( $selected ? 'active' : '' ) . '" data-value="' . ((string) $key) . '">' . $link . '</li>';
	
	
			// Add the option object to the result set.
			$options[] = $tmp;
		}
	
		reset($options);
	
		return $options;
	}
	
	/**
	 * Render contents of <li>
	 * @param unknown_type $element
	 * @return string
	 */
	protected function getOption( $option ) {
		return trim((string) $option);
	}
}