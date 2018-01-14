<?php
/*
	<id>underdog:PersonalizedBBC</id>
	<name>Personalized BBC</name>
	<version>1.93</version>
	<type>modification</type>
*/

/*
 * Personalized BBC was developed for SMF forums c/o Chen Zhen @ https://web-develop.ca
 * Copyright 2014 - 2018 noreply@web-develop.ca
 * Distributed under the CC BY-ND 4.0 License (http://creativecommons.org/licenses/by-nd/4.0/)
*/

// Use this file by using SSI.php
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

/*  This file is for mysql setup */
global $smcFunc, $modSettings;
db_extend('packages');
$bbc_hook = version_compare((!empty($modSettings['smfVersion']) ? substr($modSettings['smfVersion'], 0, 3) : '2.0'), '2.1', '<') ? 'PersonalizedBBC_codes' : '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_codes';

// Create table and columns if they do not exist
$columns = array(
	array(
	    'name' => 'name',
	    'type' => 'varchar',
	    'size' => 255,
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'description',
	    'type' => 'varchar',
	    'size' => 255,
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'code',
	    'type' => 'text',
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'image',
	    'type' => 'varchar',
	    'size' => 255,
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'prior',
	    'type' => 'text',
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'after',
	    'type' => 'text',
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'parse',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'trim',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'type',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'block_lvl',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'enable',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'display',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'view_source',
	    'type' => 'int',
	    'size' => 10,
	    'null' => false,
	    'default' => 0,
	    'unsigned' => true,
	    'auto' => false,
	),
	array(
	    'name' => 'url_fix',
	    'type' => 'varchar',
	    'size' => 255,
	    'null' => false,
	    'unsigned' => true,
	    'auto' => false,
	    'default' => "rfc0",
	)
);
$indexes = array(
	array(
	    'type' => 'primary',
	    'columns' => array('name')
	),
);

$smcFunc['db_create_table']('{db_prefix}personalized_bbc', $columns, $indexes, array(), 'ignore');

/*  Add extra needed columns into existing tables for columns native to Personalized BBC */
/*  This is necessary due to possible manual deletion or otherwise  */
foreach ($columns as $column => $data)
{
    $smcFunc['db_add_column']('{db_prefix}personalized_bbc',
	array(
	    'name' => $data['name'],
	    'type' => $data['type'],
	    'size' => !empty($data['size']) ? $data['size'] : '',
	    'unsigned' => $data['unsigned'],
	    'null' => $data['null'],
	    'auto' => $data['auto'],
	),
	array(),
	'ignore',
	'fatal'
    );
}

/* Adjust max characters of the permission column to 50 (if currently less than)    */
/* Maximum bbc name will be 25 chars (-3 of actual allowed)                         */
$permissionLength = $smcFunc['db_list_columns'] ('{db_prefix}permissions', 'detail');
if ((!empty($permissionLength)) && (int)$permissionLength['permission']['size'] < 50)
{
    $column_info = array(
	'name' => 'permission',
	'type' => 'varchar',
	'size' => 50,
	'null' => false,
	'unsigned' => true,
	'auto' => false,
    );

    $smcFunc['db_change_column'] ('{db_prefix}permissions', 'permission', $column_info);
}

/* Insert integration hooks */
add_integration_function('integrate_pre_include', '$sourcedir/PersonalizedBBC.php');
add_integration_function('integrate_pre_load', 'PersonalizedBBC_load');
add_integration_function('integrate_load_permissions', 'PersonalizedBBC_load_permissions');
add_integration_function('integrate_admin_areas', 'PersonalizedBBC_admin_areas');
add_integration_function('integrate_bbc_codes', $bbc_hook);
add_integration_function('integrate_bbc_buttons', 'PersonalizedBBC_buttons');
add_integration_function('integrate_load_theme', 'PersonalizedBBC_load_theme');

if ($bbc_hook === '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_codes')
    add_integration_function('integrate_pre_parsebbc', '$sourcedir/PersonalizedBBC.php|PersonalizedBBC_message');
?>