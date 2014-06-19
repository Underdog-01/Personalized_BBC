<?php
/*
	<id>underdog:PersonalizedBBC</id>
	<name>Personalized BBC</name>
	<version>1.5</version>
	<type>modification</type>
*/

/*
 * Personalized BBC was developed for SMF forums c/o Underdog @ http://web-develop.ca
 * Copyright 2014 underdog@web-develop.ca
 * Distributed under the CC BY-ND 4.0 License (http://creativecommons.org/licenses/by-nd/4.0/)
*/
function template_PersonalizedBBC_Admin_above()
{
	global $txt;

	// Create the tabs for the template
	echo '
	<div class="cat_bar">
		<h3 class="catbg" style="text-align:center;">', $txt['PersonalizedBBC_tabtitle'], '</h3>
	</div>
	<div class="title_bar">
		<h4 class="titlebg" style="text-align:center;">', $txt['PersonalizedBBC_tabtitle_rev'], '</h4>
	</div>
	<div id="help_container">
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div id="pbcmain">';
}

// Personalized BBC settings list
function template_PersonalizedBBC_List()
{
	global $txt, $scripturl, $context, $settings;

	echo '
				<script type="text/javascript"><!-- // --><![CDATA[
					function confirmSubmit()
					{
						var agree=confirm("' . $txt['personalizedBBC_confirm'] . '");
						if (agree)
							return true ;
						else
							return false ;
					}
				// ]]></script>
				<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
					<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="4" width="100%">
									<tr class="titlebg" style="width:100%;">
										<td colspan="5">
											', $context['settings_title'], '
											<a href="#" id="page_top"></a>
										</td>
									</tr>
									<tr class="windowbg2" align="center">
										<td>', $txt['PersonalizedBBC_tabtitle_list'], '</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="5"><hr /></td>
									</tr>
								</table>
								<table border="1" cellspacing="0" cellpadding="4" width="100%">
									<tr class="catbg2">
										<td width="19%"><u>' , $txt['personalizedBBC_name'] , '</u></td>
										<td width="51%"><u>' , $txt['personalizedBBC_bbc'] , '</u></td>
										<td width="10%" align="center"><u>' , $txt['personalizedBBC_enable'] , '</u></td>
										<td width="10%" align="center"><u>' , $txt['personalizedBBC_display'] , '</u></td>
										<td width="10%" align="center"><u>' , $txt['personalizedBBC_delete'] , '</u></td>
									</tr>';

	foreach ($context['personalizedBBC'] as $i => $tag)
	{
		if (empty($context['personalizedBBC'][$i]['name']))
			continue;

		echo '
									<tr>
										<td class="windowbg">
											<a href="' , $scripturl , '?action=admin;area=PersonalizedBBC;sa=personalizedBBC_Entry;' , $context['session_var'] , '=' , $context['session_id'] , ';name=', $context['personalizedBBC'][$i]['name'], ';">' , $context['personalizedBBC'][$i]['name'] , '</a>
										</td>
										<td class="windowbg">
											', str_replace('#%^@!', $context['personalizedBBC'][$i]['name'], $txt['personalizedBBC_type_display'][$context['personalizedBBC'][$i]['type']]), '
										</td>
										<td class="windowbg" align="center">
											<input type="checkbox" name="enable[', $context['personalizedBBC'][$i]['name'], ']" id="enable_', $context['personalizedBBC'][$i]['name'], '" class="check"';

		if (!empty($context['personalizedBBC'][$i]['enable']))
			echo '	checked />';

		else
			echo ' />';

		echo '
										</td>
										<td class="windowbg" align="center">
											<input type="checkbox" name="display[', $context['personalizedBBC'][$i]['name'], ']" id="display_', $context['personalizedBBC'][$i]['name'], '" class="check"';

		if (!empty($context['personalizedBBC'][$i]['display']))
			echo ' checked />';

		else
			echo ' />';

		echo '
										</td>
										<td class="windowbg" align="center">
											<span style="display:none;">
												<input type="text" name="current_name[', $context['personalizedBBC'][$i]['name'], ']" value="', $context['personalizedBBC'][$i]['name'], '" />
												<input type="text" name="list[', $context['personalizedBBC'][$i]['name'], ']" value="1" />
											</span>
											<input type="checkbox" name="delete[', $context['personalizedBBC'][$i]['name'], ']" class="check" />
										</td>
									</tr>';
	}

	echo '
								</table>
								<table border="0" cellspacing="0" cellpadding="4" width="100%">
									<tr>
										<td colspan="5"><hr /></td>
									</tr>
									<tr>
										<td colspan="1" style="float:left;">
											<a href="' , $scripturl , '?action=admin;area=PersonalizedBBC;sa=personalizedBBC_Entry;' , $context['session_var'] , '=' , $context['session_id'] , ';"><img style="position:relative;bottom:-2.05em;" src="', $settings['default_theme_url'],'/images/admin/personalizedBBC_add.png" alt="' , $txt['PersonalizedBBC_add'], '" title="' , $txt['PersonalizedBBC_add'], '" /></a>
										</td>
									</tr>
									<tr>
										<td colspan="4" style="float:right;">
											<input class="button_submit" type="submit" value="', $txt['personalizedBBC_submit'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' onclick="check=confirmSubmit();if(!check){return false;}" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
				</form>', $context['PersonalizedBBC_display']['page'];
}

// Personalized BBC revision
function template_PersonalizedBBC_Edit()
{
	global $txt, $scripturl, $context, $settings;

	echo '
				<form action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
					<table width="80%" border="0" cellspacing="0" cellpadding="0" class="tborder" align="center">
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="4" width="100%">
									<tr class="titlebg" style="width:100%;">
										<td colspan="3">', $context['settings_title'], '</td>
									</tr>
									<tr class="windowbg2" align="center">
										<td colspan="3">', $txt['PersonalizedBBC_tabtitle_rev'], '</td>
									</tr>
									<tr>
										<td colspan="3"><hr /></td>
									</tr>
								</table>
								<table border="0" cellspacing="0" cellpadding="4" width="100%">
									', (!empty($_SESSION['personalizedBBC_duplicate_error']) ? '
									<tr>
										<td width="2%">&nbsp;</td>
										<td colspan="2" style="width:90%;">
											' . $txt['PersonalizedBBC_DuplicateErrorMessage'] . '
										</td>
									</tr>' : ''), '
									', (!empty($_SESSION['personalizedBBC_length_error']) ? '
									<tr>
										<td width="2%">&nbsp;</td>
										<td colspan="2" style="width:90%;">
											' . $txt['PersonalizedBBC_LengthErrorMessage'] . '
										</td>
									</tr>' : ''), '
									', (!empty($_SESSION['personalizedBBC_illegal_error']) ? '
									<tr>
										<td width="2%">&nbsp;</td>
										<td colspan="2" style="width:90%;">
											' . $txt['PersonalizedBBC_IllegalErrorMessage'] . '
										</td>
									</tr>' : ''), '
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagHelp" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_name'], '
										</td>
										<td width="70%">
											<input type="text" size="50" name="name[', $context['current_name'],']" value="', $context['current_name'] ,'" />
										</td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagDescription" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_description'], '
										</td>
										<td width="70%">
											<input type="text" size="50" name="description[', $context['personalizedBBC']['current_name'],']" value="', $context['personalizedBBC']['description'],'" />
										</td>
									</tr>
									<tr>
										<td colspan="3"><hr /></td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagType" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_type'], '
										</td>
										<td width="70%">
											<select name="type[', $context['personalizedBBC']['current_name'],']">
												<option value="0" ', ($context['personalizedBBC']['type'] == 0 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_type_options'][0], '
												</option>
												<option value="1" ', ($context['personalizedBBC']['type'] == 1 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_type_options'][1], '
												</option>
												<option value="2" ', ($context['personalizedBBC']['type'] == 2 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_type_options'][2], '
												</option>
												<option value="3" ', ($context['personalizedBBC']['type'] == 3 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_type_options'][3], '
												</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagParse" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_parse'], '
										</td>
										<td width="70%">
											<select name="parse[', $context['personalizedBBC']['current_name'],']">
												<option value="0" ', ($context['personalizedBBC']['parse'] == 0 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_parse_options'][0], '
												</option>
												<option value="1" ', ($context['personalizedBBC']['parse'] == 1 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_parse_options'][1], '
												</option>
												<option value="2" ', ($context['personalizedBBC']['parse'] == 2 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_parse_options'][2], '
												</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagTrim" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_trim'], '
										</td>
										<td width="70%">
											<select name="trim[', $context['personalizedBBC']['current_name'],']">
												<option value="0" ', ($context['personalizedBBC']['trim'] == 0 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_trim_options'][0], '
												</option>
												<option value="1" ', ($context['personalizedBBC']['trim'] == 1 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_trim_options'][1], '
												</option>
												<option value="2" ', ($context['personalizedBBC']['trim'] == 2 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_trim_options'][2], '
												</option>
												<option value="3" ', ($context['personalizedBBC']['trim'] == 3 ? 'selected="selected"' : ''), '>
													', $txt['personalizedBBC_trim_options'][3], '
												</option>
											</select>
										</td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagBlockLvl" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_blockLvl'], '
										</td>
										<td width="70%">
											<input type="checkbox" name="block_lvl[', $context['personalizedBBC']['current_name'],']" value="1"', (!empty($context['personalizedBBC']['block_lvl']) ? ' checked="checked"' : ''),' />
										</td>
									</tr>
									<tr>
										<td colspan="3"><hr /></td>
									</tr>
									<tr>
										<td width="2%">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagCode" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_code'], '
										</td>
										<td>
											<textarea name="code[', $context['personalizedBBC']['current_name'],']" rows="4" cols="50" tabindex="5">', $context['personalizedBBC']['code'], '</textarea>
										</td>
									</tr>
									<tr>
										<td style="width:2%;">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_tagImage" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td width="20%">
											', $txt['personalizedBBC_image'], '
										</td>
										<td class="centertext" style="top:0.5em;;position:relative;">
											<select name="image[', $context['personalizedBBC']['current_name'], ']" id="opt" onchange="show_image()" style="display:block;position:relative;">';

	// Get all images for the dropdown list
	foreach ($context['PersonalizedBBC_Images'] as $image)
		echo '
												<option value="', $image, '"', ($context['personalizedBBC']['image'] === $image ? ' selected="selected"' : ''), '>
													', $image, '
												</option>';

	echo '
											</select>
											<span style="position:relative;text-align:left;bottom:1.6em;">
												<img id="icon" src="', $settings['default_theme_url'], '/images/bbc/personalizedBBC/', $context['personalizedBBC']['image'], '" border="1" style="max-height:20px;max-width:20px;" alt="" />
											</span>
										</td>
									</tr>
									<tr>
										<td colspan="3"><hr /></td>
									</tr>
								</table>
								<table>
									<tr>
										<td style="width:2%;">
											<a href="',$scripturl,'?action=helpadmin;help=personalizedBBC_membergroups" onclick="return reqWin(this.href);" style="text-decoration:none;">
												<img style="vertical-align:middle;position:relative;bottom:1px;width:12px;height:12px;" src="'.$settings['default_theme_url'].'/images/admin/personalizedBBC-help.gif" alt="?" />
											</a>
										</td>
										<td colspan="3" style="float:left;">
											&nbsp;&nbsp;', $txt['personalizedBBC_membergroups'], '
										</td>
									</tr>
									<tr>
										<td colpsan="4">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td colspan="2" class="titlebg">
											', $txt['personalizedBBC_membergroups_view'], '
										</td>
										<td class="titlebg">
											', $txt['personalizedBBC_membergroups_use'], '
										</td>
									</tr>';
	foreach ($context['PersonalizedBBC_Membergroups'] as $gpId => $group)
	{
		echo '
									<tr>
										<td>&nbsp;</td>
										<td colspan="2">
											<input class="checkbox_view" type="checkbox" name="membergroups_view[', $gpId, ']" value="1"', (!empty($context['personalizedBBC']['permissions_view'][$gpId]['add_deny']) ? ' checked="checked"' : ''),' />
											<span style="left:1em;bottom:0.2em;position:relative;">', $group, '</span>
										</td>
										<td>
											<input class="checkbox_use" type="checkbox" name="membergroups_use[', $gpId, ']" value="1"', (!empty($context['personalizedBBC']['permissions_use'][$gpId]['add_deny']) ? ' checked="checked"' : ''),' />
											<span style="left:1em;bottom:0.2em;position:relative;">', $group, '</span>
										</td>
									</tr>';
	}
	echo '
									<tr>
										<td>&nbsp;</td>
										<td colspan="2">
											<input type="checkbox" id="membergroup_check_view" name="membergroup_view_check" onclick="checkToggle(\'view\');return true;" value="1" />
											<span style="left:1em;bottom:0.2em;position:relative;">', $txt['personalizedBBC_membergroups_check'], '</span>
										</td>
										<td>
											<input type="checkbox" id="membergroup_check_use" name="membergroup_use_check" onclick="checkToggle(\'use\');return true;" value="1" />
											<span style="left:1em;bottom:0.2em;position:relative;">', $txt['personalizedBBC_membergroups_check'], '</span>
										</td>
									</tr>
								</table>
								<table border="0" cellspacing="0" cellpadding="4" width="100%">
									<tr>
										<td colspan="3"><hr /></td>
									</tr>
									<tr>
										<td colspan="3">
											<input class="button_submit" type="submit" value="', $txt['personalizedBBC_submit'], '"', (!empty($context['save_disabled']) ? ' disabled="disabled"' : ''), ' />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<span style="display:none;">
						<input type="text" name="current_name[', $context['personalizedBBC']['current_name'], ']" value="', $context['personalizedBBC']['current_name'], '" />
						<input type="text" name="list[', $context['personalizedBBC']['current_name'], ']" value="0" />
					</span>
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
				</form>
				<script type="text/javascript" language="javascript"><!-- // --><![CDATA[
					function show_image()
					{
						var myopt = document.getElementById("opt");
						var myimage = document.getElementById("icon");
						if (myopt.value !== "', $context['PersonalizedBBC_Images'][0], '")
						{
						    var image_url = "', $settings['default_theme_url'], '/images/bbc/personalizedBBC/" + myopt.value;
						    myimage.src = image_url;
						    myimage.style = "display:inline;max-height:20px;max-width:20px;";
						}
						else
						{
						    myimage.src = "', $settings['default_theme_url'], '/images/bbc/personalizedBBC/', $context['PersonalizedBBC_Images'][0],'";
						    myimage.style = "height:0px;width:0px;";
						}
					}
					function checkToggle(intent)
					{
						var toggle = document.getElementsByName("membergroup_" + intent);
						var field = document.getElementsByClassName("checkbox_" + intent);
						var checksall = document.getElementById("membergroup_check_" + intent);

						for (i = 0; i < field.length; i++)
						{
							if (checksall.checked == false)
								field[i].checked = false;
							else
								field[i].checked = true;
						}
					}
				// ]]></script>';
}

function template_PersonalizedBBC_Admin_below()
{
	echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>
	</div>';
}
?>