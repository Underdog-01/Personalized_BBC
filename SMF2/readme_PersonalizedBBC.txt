[font=impact][size=18pt][color=teal]SMF 2.0x - Personalized BBC[/color][/size][/font]

Purpose and/or usage of this software package:

The purpose of this application is to allow an administrator the option to add personalized BBCs to a SMF forum installation.
These BBC additions have individual permission settings to allow or restrict their usage and visibility.

[hr]

Recommended minimal requirements:
[table]
[tr][td]Server: [/td][td]PHP 5.3+[/td][/tr]
[tr][td] [/td][td]MYSQL 5.0+ using MyISAM or InnoDB engine[/td][/tr]
[tr][td]Browser: [/td][td]HTML5 capability[/td][/tr]
[tr][td]SMF Version: [/td][td]2.0.7+[/td][/tr]
[/table]

[hr]

Developed for SMF forums c/o Underdog @ [url=http://web-develop.ca]web-develop.ca[/url]
Copyright 2014 Underdog@web-develop.ca
Distributed under the [url=http://creativecommons.org/licenses/by-nd/4.0/]CC BY-ND 4.0 License[/url]

If you commend this software package and/or any other contributions that underdog@webdevelop.comli.com develops for the SMF community,
please feel free to make a donation to paypal using the image/link provided below. Thank you for opting to use this software package.

[url=http://web-develop.ca/index.php?page=underdog_donation][img]https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif[/img][/url]

[hr]

Features:

[list]
[li]Ability to add Personalized Bulletin Board Codes[/li]
[li]Permission settings for each Personalized Bulletin Board Code[/li]
[/list]

[hr]

Changelog:

[Version 1.2]
! fixed images list within template and added natsort
! fixed issues regarding some inputs within templates
! fixed regex regarding permission disabled closed tags
! ensure bbc name variable(s) are lowercase
! max bbc name of 35 characters in length
! installer adjusts permission column within permissions table to 60 character max (mod-var_bbc-name_ilk)

[Version 1.1]
+ separate view & usage permission settings
! removed smcFunc escape/unescape
! natural sorting of Personalized BBC's for admin list and permissions
! checks against existing SMF default BBC's
! fixed deletion of first entry issue
! fixed check-all in admin template

[Version 1.0]
+ Initial release
[hr]

Disclaimers:

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.