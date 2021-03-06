<?php
/**
 * Default search page.
 * 
 * Override to edit the JSolrSearch home page.
 * 
 * @copyright	Copyright (C) 2012-2013 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolrSearch Component for Joomla!.

   The JSolrSearch Component for Joomla! is free software: you can redistribute it 
   and/or modify it under the terms of the GNU General Public License as 
   published by the Free Software Foundation, either version 3 of the License, 
   or (at your option) any later version.

   The JSolrSearch Component for Joomla! is distributed in the hope that it will be 
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolrSearch Component for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<hayden@knowledgearc.com>
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('behavior.formvalidation');

$form = $this->get('Form');
defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();

$document->addStyleSheet(JURI::base().'/media/com_jsolrsearch/css/jsolrsearch.css');
?>
<section id="jsolrSearch">
	<form action="<?php echo JRoute::_("index.php"); ?>" method="get" name="adminForm" class="form-validate jsolr-search-result-form" id="jsolr-search-result-form">				
		<fieldset class="word">
			<?php foreach ($this->get('Form')->getFieldset('query') as $field): ?>
			<span><?php echo $form->getInput($field->fieldname); ?></span>
			<?php endforeach;?>
			
			<input type="hidden" name="option" value="com_jsolrsearch"/>
			<input type="hidden" name="task" value="search"/>
			
			<button type="submit" class="button"><?php echo JText::_("COM_JSOLRSEARCH_BUTTON_SUBMIT"); ?></button>
		</fieldset>
	
		<div class="jsolr-clear"></div>
	</form>
</section>