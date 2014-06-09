<?php
/*
	<id>underdog:PersonalizedBBC</id>
	<name>Personalized BBC</name>
	<version>1.0</version>
	<type>modification</type>
*/

/*
 * Personalized BBC was developed for SMF forums c/o Underdog @ http://web-develop.ca
 * Copyright 2014 underdog@web-develop.ca
 * Distributed under the CC BY-ND 4.0 License (http://creativecommons.org/licenses/by-nd/4.0/)
*/
global $helptxt;

/* Personalized BBC general text variables */
$txt['PersonalizedBBC_tabtitle'] = 'Personalized BBC';
$txt['PersonalizedBBC_tabtitle_list'] = 'Personalized BBC List';
$txt['PersonalizedBBC_tabtitle_rev'] = 'Personalized BBC Revision';
$txt['PersonalizedBBC_Settings'] = 'Personalized BBC Settings';
$txt['PersonalizedBBC_AdminSettings'] = 'Settings';
$txt['PersonalizedBBC_add'] = '<< Create New BBCode >>';
$txt['PersonalizedBBC'] = 'personalized_bbc_';
$txt['personalizedBBC_confirm'] = 'Are you sure you want to save these changes?';
$txt['personalizedBBC_name'] = 'BBCode Name';
$txt['personalizedBBC_description'] = 'Description';
$txt['personalizedBBC_type'] = 'Type';
$txt['personalizedBBC_parse'] = 'Parsing';
$txt['personalizedBBC_trim'] = 'Trim whitespace';
$txt['personalizedBBC_blockLvl'] = 'Block Level';
$txt['personalizedBBC_code'] = 'BBC HTML';
$txt['personalizedBBC_bbc'] = 'BBCode Usage';
$txt['personalizedBBC_enable'] = 'Enable';
$txt['personalizedBBC_display'] = 'Show';
$txt['personalizedBBC_delete'] = 'Delete';
$txt['personalizedBBC_allow'] = 'Allow';
$txt['personalizedBBC_deny'] = 'Deny';
$txt['personalizedBBC_submit'] = 'Submit';
$txt['personalizedBBC_image'] = 'BBC Icon';
$txt['personalizedBBC_membergroups'] = 'Membergroup Permissions';
$txt['personalizedBBC_membergroups_view'] = 'Viewing';
$txt['personalizedBBC_membergroups_use'] = 'Usage';
$txt['personalizedBBC_membergroups_check'] = 'Check/Uncheck All';
$txt['PersonalizedBBC_page'] = 'Page';

// drop-down options
$txt['personalizedBBC_type_options'] = array('[tag]content[/tag]', '[tag=option]content[/tag]', '[tag=option1,option2]content[/tag]', '[tag]');
$txt['personalizedBBC_type_display'] = array('[#%^@!]content[/#%^@!]', '[#%^@!=option]content[/#%^@!]', '[#%^@!=option1,option2]content[/#%^@!]', '[#%^@!]');
$txt['personalizedBBC_parse_options'] = array('no parsing', '{content} only', '{content} and {option}');
$txt['personalizedBBC_trim_options'] = array('none', 'inside', 'outside', 'both');

// error message
$txt['PersonalizedBBC_ErrorMessage'] = 'You do not have permission to access the Personalized BBC Admin section.';
$txt['PersonalizedBBC_PHP_ErrorMessage'] = 'Your PHP version is too old, please upgrade to a newer version.<br />Your current version is #@#$! whereas this plugin requires a minimum version of 5.3.0';
$txt['PersonalizedBBC_DuplicateErrorMessage'] = '<span class="error">Warning:</span> This BBC already exists within the SMF defaults, please opt another BBC tag name.';

// permissions
$txt['permissiongroup_PersonalizedBBC_perms'] = 'Personalized BBC';
$txt['permissiongroup_simple_PersonalizedBBC_perms'] = 'Personalized BBC';
$txt['permissionname_PersonalizedBBC_perm_view'] = 'View BB Code: #@#$!';
$txt['permissionname_PersonalizedBBC_perm_use'] = 'Use BB Code: #@#$!';
$txt['permissionhelp_PersonalizedBBC_perm_view'] = 'Allow this membergroup to view the #@#$! BB Code.';
$txt['permissionhelp_PersonalizedBBC_perm_use'] = 'Allow this membergroup to use the #@#$! BB Code.';
$txt['PersonalizedBBC_perms'] = 'Personalized BB Codes';

// help text
$helptxt['personalizedBBC_tagHelp'] = 'The designation of the BBC tag. This input is restrictive whereas only letters, numbers and underscores are allowed. Spaces entered within this input will be converted to underscores.';
$helptxt['personalizedBBC_tagDescription'] = 'The description of the BBC tag. This will be displayed to users when they hover over this BB Code.';
$helptxt['personalizedBBC_tagType'] = 'Select the BBC type that will be used whereas the type descriptions are fairly self explanatory.<br /><br />Multiple instances of {content} within your code will most likely require the "no parsing" option.<br /><br />Switching between "parsing" and "no parsing" may have undesired effects on the code that was inputted, therefore one should verify the code is correct if that option has been changed.<hr /><hr />Closed BBC tags will automatically be set with no parsing.';
$helptxt['personalizedBBC_tagParse'] = 'Select the type of parsing to be used which will determine this BB Code\'s behavior.<br /><br />no parsing: User input between tags will be ignored (Only HTML entered in code setting will be displayed).<br /><br />{content} only: {content} will be replaced by what a user enters between bbc tags.<br /><br />{content} and {option}: {content} will be replaced by what a user enters between bbc tags. {option} will be replaced by what a user enters after `=` within an initial bbc tag.';
$helptxt['personalizedBBC_tagTrim'] = 'Select how any whitespace will be trimmed from the BB Code text output.<br /><br />This option will edit your bbc code when saved within admin.<br /><br />none: Will not trim your code.<br /><br />inside: Trim any whitespace between HTML tags.<br /><br />outside: Trim any whitespace outside any HTML tags.<br /><br />both: Trim all whitespace.';
$helptxt['personalizedBBC_tagBlockLvl'] = 'Opt whether a block level display will be implemented.';
$helptxt['personalizedBBC_tagCode'] = 'The HTML to be used for the BBC.<br />ie. &lt;tag&gt;{content}&lt;/tag&gt; or &lt;tag alt={option}&gt;{content}&lt;/tag&gt;';
$helptxt['personalizedBBC_tagImage'] = 'Select the image to be used for this BBC.  Default images are included with this modification, however custom images can be added to the following directory: ../Themes/default/images/bbc/PersonalizedBBC. Only gif file types are acceptable.';
$helptxt['personalizedBBC_membergroups'] = 'The membergroups that are enabled/checked will have permission to use and view this BB Code. The unchecked/disabled membergroups will not be able to use this BB Code nor will they be able to view any content contained within them.';
?>