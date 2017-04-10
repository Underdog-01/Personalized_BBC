Personalized BBC

Purpose and/or usage of this software package:

The purpose of this application is to allow an administrator the option to add personalized BBCs to a SMF forum installation.  These BBC additions have individual permission settings to allow or restrict their usage and visibility.

[Important Notation]:
  This modification will only hide Personalized BBC content to specified membergroups when it is installed.
  Prior to using the SMF Large Upgrade Package it is advised to put your forum in maintenance mode
  until you are able to reinstall this modification.
  Failing to follow that procedure will allow all membergroups with appropriate board permissions
  to view all those BBC's that were normally hidden.
  The same applies when uninstalling this modification.

Recommended minimal requirements:

Server:  PHP 5.3+ 
Database: MYSQL 5.0+ using MyISAM or InnoDB engine
Browser: HTML5 capability
SMF Version: 2.0.X - 2.1.X

Developed for SMF forums c/o Underdog@web-develop.ca
Copyright 2014 Underdog@web-develop.ca
Distributed under the CC BY-ND 4.0 License ( http://creativecommons.org/licenses/by-nd/4.0/ )

Features:

+ Ability to add Personalized Bulletin Board Codes
+ Permission settings for each Personalized Bulletin Board Code


Changelog:

[version 1.92]
- fixed IE compatibilty for DOM style elements
- changed window.onload to addEventListener/attachEvent

[version 1.91]
+ added javascript for optional BBCode parameters

[Version 1.9]
+ added option to enable external jQuery support (SMF 2.0.X only)

[Version 1.8]
+ added option to test bbcode within revision template
+ added onmouseover/onmouseout display events for icons in the bbcode list
+ js and css files added for use with the above additions
+ added option to enable/disable autolink
+ added index.php files for the SMF 2.1 directories
+ newly created bbcodes default to all allowed permissions
+ disabled override for this version
+ adjusted css within admin templates

[Version 1.7]
+ added option to upload images within tag settings
+ uploaded images resized to 20px x 20px dimensions
+ ../images/bbc/personalizedBBC folder not removed during uninstall to preserve uploaded images
+ adjusted installer for minor issues/discrepancies
+ fixed SMF 2.1 installation discrepancies

[Version 1.6]
+ mod now filters restricted bbcodes outside of posts/pm's
+ added option to allow filtering of Url entered within code input (option to conform to RFC 3986 standards)
+ added IMDb and TMDb icons
+ clean_cache() function implemented for permission changes to immediately take affect
+ use of un_htmlspecialchars() function for adding BBCode HTML (HTML within bbc will not function properly without this method being implemented)

[Version 1.5]
+ added SMF 2.1 support
+ includes png bbc icons for SMF 2.1 support
+ added override option for existing SMF default BBC tags
+ fixed BBC button hook routine
+ fixed BBC type setting for newly created tag
+ various language and file changes for SMF 2.1 compatibility
+ changed regex for closed tags regarding HTML5 compatibility

[Version 1.4]
+ added [Important Notation] to this readme
+ changed routine for gathering SMF default BBC's
+ changed routine for adjusting permissions
+ max allowed BBC tag name length now 25 characters (due to display template)
+ removed unnecessary filter_var() usage (regex sanitizes this variable)
+ installer now includes admin icon for all custom themes
+ fixed source filenames withing uninstall xml
+ fixed editing of same BBC tag name from last update

[Version 1.3]
+ relays error message for characters not allowed in BBC names
+ fixed existing bbc check to include BBC's not contained within $context['bbc_tags']

[Version 1.2]
+ fixed images list within template and added natsort
+ fixed issues regarding some inputs within templates
+ fixed regex regarding permission disabled closed tags
+ ensure bbc name variable(s) are lowercase
+ max bbc name of 35 characters in length
+ installer adjusts permission column within permissions table to 60 character max (mod-var_bbc-name_ilk)

[Version 1.1]
+ separate view & usage permission settings
+ removed smcFunc escape/unescape
+ natural sorting of Personalized BBC's for admin list and permissions
+ checks against existing SMF default BBC's
+ fixed deletion of first entry issue
+ fixed check-all in admin template

[Version 1.0]
+ Initial release

Disclaimers:

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
