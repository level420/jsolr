<?php
/**
 * Provides the base for the search results display.
 * 
 * Loads the form, facet filters, facets, results and pagination templates.
 * 
 * @package		JSolr
 * @subpackage	Search
 * @copyright	Copyright (C) 2012 Wijiti Pty Ltd. All rights reserved.
 * @license     This file is part of the JSolrSearch Component for Joomla!.
 *
 *   The JSolrSearch Component for Joomla! is free software: you can redistribute it 
 *   and/or modify it under the terms of the GNU General Public License as 
 *   published by the Free Software Foundation, either version 3 of the License, 
 *   or (at your option) any later version.
 *
 *   The JSolrSearch Component for Joomla! is distributed in the hope that it will be 
 *   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with the JSolrSearch Component for Joomla!.  If not, see 
 *   <http://www.gnu.org/licenses/>.
 *
 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * @author Hayden Young <haydenyoung@wijiti.com>
 * @author Bartłomiej Kiełbasa <bartlomiejkielbasa@wijiti.com>
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();

$document->addScript(JURI::base().'/media/jsolr/js/dropdown.js');

$document->addStyleSheet(JURI::base().'/media/com_jsolrsearch/css/jsolrsearch.css');
$document->addStyleSheet(JURI::base().'/media/jsolr/css/dropdown.css');

$document->addScriptDeclaration('
jQuery(document).ready(function() {
	var jsolrsearch_autocomplete_url = "'.JRoute::_('index.php?option=jsolrsearch&view=basic').'";
	var jsolrsearch_search_url = "'.JRoute::_('index.php?option=jsolrsearch&view=basic').'";
});
');
?>

<section id="jsolrSearchResults">
	<header>
		<?php echo $this->loadTemplate('form'); ?>
	
		<div id="jsolrFacetfilters">
		   <?php echo $this->loadTemplate('facetfilters'); ?>
		</div>
	
		<?php if ($this->showFacets) : ?>
		   <div id="jsolrFacets">
		      <?php echo $this->loadTemplate('facets'); ?>
		   </div>
		<?php endif; ?>
	</header>

	<?php if (!is_null($this->items)): ?>
		<?php echo $this->loadResultsTemplate(); ?>
	<?php endif ?>
		      
	<footer>
		<div class="pagination">
		<?php echo $this->get('Pagination')->getPagesLinks(); ?>
		</div>
	</footer>
</section>