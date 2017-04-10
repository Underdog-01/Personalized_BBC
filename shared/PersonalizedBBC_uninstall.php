<?php
/*
	<id>underdog:PersonalizedBBC</id>
	<name>Personalized BBC</name>
	<version>1.9</version>
	<type>modification</type>
*/

/*
 * Personalized BBC was developed for SMF forums c/o Underdog @ http://web-develop.ca
 * Copyright 2014 underdog@web-develop.ca
 * Distributed under the CC BY-ND 4.0 License (http://creativecommons.org/licenses/by-nd/4.0/)
*/

/*  This file is for removing integration hooks */

// Use this file by using SSI.php
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

global $modSettings;
$bbc_hook = version_compare((!empty($modSettings['smfVersion']) ? substr($modSettings['smfVersion'], 0, 3) : '2.0'), '2.1', '<') ? 'PersonalizedBBC_codes' : '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_codes';
/* Remove integration hooks */
remove_integration_function('integrate_pre_include', '$sourcedir/PersonalizedBBC.php');
remove_integration_function('integrate_pre_load', 'PersonalizedBBC_load');
remove_integration_function('integrate_load_permissions', 'PersonalizedBBC_load_permissions');
remove_integration_function('integrate_admin_areas', 'PersonalizedBBC_admin_areas');
remove_integration_function('integrate_bbc_codes', $bbc_hook);
remove_integration_function('integrate_bbc_buttons','PersonalizedBBC_buttons');

if ($bbc_hook === '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_codes')
    remove_integration_function('integrate_pre_parsebbc', '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_message');
?>