<?php 
/**
 * Prepares a minimalist framework for unit testing.
 *
 * Joomla is assumed to include the /unittest/ directory.
 * eg, /path/to/joomla/unittest/
 *
 * @version		$Id: bootstrap.php 14408 2010-01-26 15:00:08Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.phpunit.de/manual/current/en/installation.html
 */

// Load the custom initialisation file if it exists.
if (file_exists('config.php')) {
	include 'config.php';
}

// Define expected Joomla constants.
define('_JEXEC',		1);

if (!defined('JPATH_BASE'))
{
	// JPATH_BASE can be defined in init.php
	// This gets around problems with soft linking the unittest folder into a Joomla tree,
	// or using the unittest framework from a central location.
	define('JPATH_BASE', dirname(dirname(__FILE__)));
}

// Fix magic quotes.
@set_magic_quotes_runtime(0);

// Maximise error reporting.

@ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include relative constants, JLoader and the jimport and jexit functions.

require_once JPATH_BASE.'/includes/defines.php';
require_once JPATH_LIBRARIES.'/joomla/import.php';
require_once JPATH_BASE . '/libraries/joomla/session/session.php';

$session = JFactory::getSession();
$hostname=(isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : exec("hostname");
//session_start();
//ob_start();