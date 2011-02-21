<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Format a content result to create a generic result.
 *
 * @version     $LastChangedBy$
 * @paackage	Wijiti
 * @subpackage	JSolr
 * @copyright   Copyright (C) 2011 inwardXpat Pty Ltd
 */

jimport('joomla.error.log');

class plgJSolrSearchJSolrVirtuemart extends JPlugin 
{
	var $_thumbnailRelPath;
	
	var $_plugin;
	
	var $_params;
	
	var $_option = 'com_virtuemart';
	
	var $_id = 0;
		
	/**
	 * Constructor
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		$this->loadLanguage(null, JPATH_ADMINISTRATOR);
		
		// load plugin parameters
		$this->_plugin = & JPluginHelper::getPlugin('jsolrsearch', 'jsolrvirtuemart');
		$this->_params = new JParameter($this->_plugin->params);

		$this->_imageURL = JURI::base()."components/com_virtuemart/shop_image/product";
		$this->_vmNoImageURL = JURI::base()."components/com_virtuemart/themes/default/images/noimage.gif";
	}

	/**
	 * Adds a custom query filter if the filter option associated 
	 * with this plugin is selected.
	 * 
	 * The method returns a value associcated with the Solr query string qf.
	 * 
	 * @return array A list of filter query fields and boost values.
	 */
	function onAddQF()
	{
		$qf = array();

		foreach ($this->_params->toArray() as $key=>$value) {
				if (strpos($key, "jsolr_boost") === 0) {
					$qfKey = str_replace("jsolr_boost_", "", $key);
					$qf[$qfKey] = floatval($value);
				}
		}

		return $qf;
	}
	
	/**
	 * Adds a list of fields to highlight.
	 * 
	 * The method returns a value associated with the Solr query string hl.fl.
	 * 
	 * @return array A list of fields to highlight.
	 */
	public function onAddHL()
	{
		$hl = array("title", "content");
		
		return $hl;
	}	
	
	/**
	 * Gets the option associated with this plugin.
	 * 
	 * The returned result takes the form 
	 * $array[option] = OPTION_TRANSLATED_TO_TEXT.
	 * 
	 * @return array The option associated with this plugin.
	 */
	public function onFilterOptions()
	{		
		static $options = array();
		$options[$this->_option] = JText::_("PLG_JSOLRSEARCH_JSOLRVIRTUEMART_COM_VIRTUEMART");
	
		return $options;
	}
	
	/**
	 * Adds a custom filter query if the filter option associated 
	 * with this plugin is selected.
	 * 
	 * The method returns a value associcated with the Solr query string fq.
	 * 
	 * @param array $params A list of query params.
	 * @param string $lang The current environment language.
	 * 
	 * @return array A list of filter queries if the current filter option is 
	 * com_virtuemart, empty otherwise.
	 */
	public function onAddFilterQuery($params, $lang)
	{
		$array = array();
		
		if (JArrayHelper::getValue($params, "o") == $this->_option) {			
			$category = JArrayHelper::getValue($params, "fcat");			
			
			if ($category) {
				$array[] = "{!raw f=category".$lang."}".trim($category);	
			}
			
			$min = floatval(JArrayHelper::getValue($params, "pmin"));
			$max = floatval(JArrayHelper::getValue($params, "pmax"));
			
			if ($min && $max) {
				$array[] = "price:[$min TO $max]";	
			}
			
			if ($min && !$max) {
				$array[] = "price:[$min TO *]";
			}
			
			if (!$min && $max) {
				$array[] = "price:[* TO $max]";
			}
		}

		return $array;
	}
	
	/**
	 * Retrieves a list of virtuemart category facets. 
	 * 
	 * @param string $lang
	 * 
	 * @return array A list of category facets with associated counts.
	 */
	public function onPrepareFacets($lang)
	{
		require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_jsolrsearch".DS."configuration.php");
		
		$configuration = new JSolrSearchConfig();
		
		$options = array(
    		'hostname' => $configuration->host,
    		'login'    => $configuration->username,
    		'password' => $configuration->password,
    		'port'     => $configuration->port,
			'path'	   => $configuration->path
		);

				
		if ($lang) {
			$lang = str_replace("-", "_", $lang);		
			$lang = "_" . $lang;
		}
		
		$facetField = "category".$lang;

		$array = array();
		
		try {
			$client = new SolrClient($options);
			$query = new SolrQuery();
					
			$query->setQuery(JRequest::getString("q"));
		
			$query->addFacetQuery("option:".$this->_option);
			
			$min = JRequest::getString("pmin", null);
			$max = JRequest::getString("pmax", null);

			if (is_numeric($min)) {
				$min = floatval($min);
			}
			
			if (!$min) {
				$min = "*";	
			}
			
			if (is_numeric($max)) {
				$max = floatval($max);
			}
					
			if (!$max) {
				$max = "*";	
			}
						
			$query->addFilterQuery("price:[$min TO $max]");
			
			$query->setFacet(true);
			$query->addFacetField($facetField);
			$query->setFacetLimit(10);
			$query->setFacetMinCount(1);
			
			$queryResponse = $client->query($query);

			$response = $queryResponse->getResponse();
			
			$array = JArrayHelper::getValue($response->facet_counts->facet_fields, $facetField, array());
		
		} catch (SolrClientException $e) {
			$log = JLog::getInstance();
			$log->addEntry(array("c-ip"=>"", "comment"=>$e->getMessage()));
		}
		
		return $array;
	}
	
	public function onPrepareCurrency()
	{
		return $this->_params->get("jsolr_default_currency");
	}

	/**
	* Search method
	*
	* Format a com_content document and return a generic result item.
	* @param mixed $document
	* @param mixed $hl
	* @param int $hlFragSize
	* @param string $lang
	*/
	function onFormatResult($document, $hl, $hlFragSize, $lang) 
	{
		$result = null;

		$option = $this->onFilterOptions();
		$keys = array_keys($option);
		
		$title = "title$lang";
		$category = "category$lang";

		if ($document->option == JArrayHelper::getValue($keys, 0)) {
			$result = new stdClass();
			
			$parts = explode(".", $document->id);
			$id = JArrayHelper::getValue($parts, 1, 0);

			$highlighting = JArrayHelper::getValue($hl, $document->id);
			
			if ($highlighting->offsetExists($title)) {
        		$hlTitle = JArrayHelper::getValue($highlighting->$title, 0);
			} else {
				$hlTitle = $document->$title;
			}
			
			$this->_setId($id);
			
			$result->title = $hlTitle;
			$result->href = JRoute::_("index.php?option=".$this->_option."&id=".$id);
			$result->text = $this->_getHlContent($document, $highlighting, $hlFragSize, $lang);
			$result->location = implode(", ", $document->$category);
			$result->created = null;
			$result->modified = null;			
			$result->attribs["price"] = $this->_formatCurrency($document->price);
			$result->attribs["currency"] = $this->_params->get("jsolr_default_currency");
			$result->attribs["thumbnail"] = $this->_buildThumbnailURL($document);
		}

		return $result;
	}
	
	private function _getHlContent($solrDocument, $highlighting, $fragSize, $lang)
	{
		$hlContent = null;

		$content = "content$lang";

		if ($highlighting->offsetExists($content)) {
			foreach ($highlighting->$content as $item) {
				$hlContent .= $item;	
			}
		}
		
		return $hlContent;
	}
	
	/**
	 * Gets the thumbnail image associated with a particular product.
	 * 
	 * @param int $id The product id.
	 */
	private function _buildThumbnailURL($document)
	{
		$url = "";

		if ($document->thumbnail) {
			$url = $this->_imageURL."/".$document->thumbnail;
		} else {
			$url = $this->_vmNoImageURL;
		}
		
		return $url;
	}
	
	private function _formatCurrency($amount)
	{
		$query = "SELECT b.vendor_currency_display_style " . 
					 "FROM #__vm_product AS a " . 
					 "INNER JOIN #__vm_vendor AS b " . 
					 "WHERE a.product_id = " . intval($this->_getId()) . ";";
		
		$database = JFactory::getDBO();
		$database->setQuery($query);

		$data = $database->loadObject();

		if (isset($data->vendor_currency_display_style) && 
			$data->vendor_currency_display_style) {
			$array = explode( "|", $data->vendor_currency_display_style);
			
			$display = Array();
			$display["id"] = @$array[0];
			$display["symbol"] = @$array[1];
			$display["nbdecimal"] = @$array[2];
			$display["sdecimal"] = @$array[3];
			$display["thousands"] = @$array[4];
			$display["positive"] = @$array[5];
			$display["negative"] = @$array[6];
				
			return number_format($amount, 
				JArrayHelper::getValue($display, "nbdecimal"), 
				JArrayHelper::getValue($display, "sdecimal"), 
				JArrayHelper::getValue($display, "thousands"));
				
		}
		
		return $amount;
	}
	
	private function _setId($id)
	{
		$this->_id = $id;
	}
	
	private function _getId()
	{
		return $this->_id;
	}
}