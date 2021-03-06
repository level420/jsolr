<?php
/**
 * Provides the search form within the search results display so that a user 
 * can modify the current search without having to start over.
 * 
 * Copy this file to override the layout and style of the search results form.
 * 
 * @copyright	Copyright (C) 2012-2014 KnowledgeARC Ltd. All rights reserved.
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
?>
<form action="<?php echo JRoute::_("index.php"); ?>" method="get" name="adminForm" class="form-validate jsolr-search-result-form" id="jsolr-search-result-form">
	<input type="hidden" name="option" value="com_jsolrsearch"/>
	<input type="hidden" name="task" value="search"/>
	
	<?php if (JFactory::getApplication()->input->get('o', null)) : ?>
	<input type="hidden" name="o" value="<?php echo JFactory::getApplication()->input->get('o'); ?>"/>
	<?php endif; ?>
	
	<fieldset class="query">
		<!-- Output search fields (in almost all cases will be a single query field). -->
		<?php foreach ($this->get('Form')->getFieldset('query') as $field): ?>
			<?php echo $this->form->getInput($field->fieldname); ?>
		<?php endforeach;?>
		
		<!-- Output the hidden form fields for the various selected facet filters. -->
		<?php foreach ($this->get('Form')->getFieldset('facets') as $field): ?>
			<?php if (trim($field->value)) : ?>
				<?php echo $this->form->getInput($field->fieldname); ?>
			<?php endif; ?>
		<?php endforeach;?>
		
		<button type="submit" class="button"><?php echo JText::_("COM_JSOLRSEARCH_BUTTON_SUBMIT"); ?></button>
	</fieldset>

	<a href="<?php echo JRoute::_(JSolrSearchFactory::getAdvancedSearchRoute()); ?>">Advanced search</a>
	
	<div class="clr"></div>

	<?php $plugins = $this->get('Plugins'); ?>

	<nav>			
		<ul>
			<?php for ($i = 0; $i < count($plugins); ++$i): ?>
			<li>
				<?php
					$isSelected = ($plugins[$i]['name'] == JFactory::getApplication()->input->get('o')) ? true : false;
				
					echo JHTML::_(
						'link', 
						$plugins[$i]['uri'], 
						JText::_($plugins[$i]['label']), 
						array(
							'data-category'=>$plugins[$i]['name'], 
							'class'=> $isSelected ? 'active' : '')); 
				?>
				</li>
        	<?php endfor ?>
		</ul>
    </nav>

	<div class="clr"></div>
			
	<div class="jsolr-searchtools">
		<?php foreach ($this->get('Form')->getFieldset('tools') as $field) : ?>
			<?php echo $this->form->getInput($field->name); ?>
		<?php endforeach;?>
	</div>
	
	<?php echo JHTML::_('form.token'); ?>
</form>

<div id="custom-dates">
	<form id="custom-dates-form" action="<?php echo JRoute::_(JSolrSearchFactory::getSearchRoute()); ?>" method="get">
		<?php echo JHtml::_('calendar', '', "qdr_min", "qdr_min", "%Y-%m-%d"); ?>
		<?php echo JHtml::_('calendar', '', "qdr_max", "qdr_max", "%Y-%m-%d"); ?>

		<button id="custom-dates-submit"><?php echo JText::_('JSUBMIT'); ?></button>
		<a id="custom-dates-cancel" href="#"><?php echo JText::_('JCANCEL'); ?></a>
	</form>
</div>