<?php

#error_reporting(E_ALL);
#ini_set("display_errors", 1);

# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# http://www.mediawiki.org/wiki/Manual:Configuration_settings

# If you customize your file layout, set $IP to the directory that contains
# the other MediaWiki files. It will be used as a base to locate files.
if( defined( 'MW_INSTALL_PATH' ) ) {
	$IP = MW_INSTALL_PATH;
} else {
	$IP = dirname( __FILE__ );
}

$path = array( $IP, "$IP/includes", "$IP/languages" );
set_include_path( implode( PATH_SEPARATOR, $path ) . PATH_SEPARATOR . get_include_path() );

require_once( "$IP/includes/DefaultSettings.php" );

# If PHP's memory limit is very low, some operations may fail.
# ini_set( 'memory_limit', '20M' );

if ( $wgCommandLineMode ) {
	if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
		die( "This script must be run from the command line\n" );
	}
}
## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;

$wgSitename         = "MHN";

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs please see:
## http://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath       = "";

$wgScriptExtension  = ".php";
## If using PHP as a CGI module, use the ugly URLs
$wgArticlePath      = "/wiki/$1";
# $wgArticlePath      = "$wgScript?title=$1";
$wgUsePathInfo      = true;

## UPO means: this is also a user preference option

$wgEnableEmail      = true;
$wgEnableUserEmail  = true; # UPO

$wgEmergencyContact = "webteam@mind-hochschul-netzwerk.de";

$wgSMTP = [
    'host'     => getenv("SMTP_SECURE") . "://" . getenv("SMTP_HOST"),
    'IDHost'   => getenv("SMTP_HOST"),
    'port'     => getenv("SMTP_PORT"),
    'auth'     => true,
    'username' => getenv("SMTP_USER"),
    'password' => getenv("SMTP_PASSWORD"),
];

$wgEnotifFromEditor = true;
$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = false;

## Database settings
$wgDBtype           = "mysql";
$wgDBserver         = getenv("MYSQL_HOST");
$wgDBname           = getenv("MYSQL_DATABASE");
$wgDBuser           = getenv("MYSQL_USER");
$wgDBpassword       = getenv("MYSQL_PASSWORD");
$wgDBprefix         = "";

## Shared memory settings
#$wgMainCacheType = CACHE_NONE;#default anyway
#$wgMemCachedServers = array();

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads       = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "de_DE.utf8";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
# $wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
$wgUseTeX           = false;

$wgLocalInterwiki   = strtolower( $wgSitename );

$wgLanguageCode = "de";

$wgSecretKey = getenv("SECRET_KEY");

$wgServer = "https://wiki." . getenv("DOMAINNAME");

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
# $wgEnableCreativeCommonsRdf = true;
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";
# $wgRightsCode = ""; # Not yet used

$wgDiff3 = "/usr/bin/diff3";

# When you make changes to this configuration file, this will make
# sure that cached pages are cleared.
$wgCacheEpoch = max( $wgCacheEpoch, gmdate( 'YmdHis', @filemtime(
__FILE__ ) ) );




/*******************************************************************************
 * Logo
 ****************************************************************************
 */
$wgStylePath = "{$wgScriptPath}/skins";
$wgLogo = "{$wgScriptPath}/logo.png";


/*******************************************************************************
 * Skins
 ****************************************************************************
*/

#1.31
wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'Vector' );
wfLoadSkin( 'Timeless' );
$wgDefaultSkin = 'Timeless';

/*******************************************************************************
 * Custom Namespaces
 ****************************************************************************
*/
# Define constants for namespace IDs:
define("NS_VORSTAND", 100); define("NS_VORSTAND_TALK", 101);
define("NS_GLOSSAR", 102); define("NS_GLOSSAR_TALK", 103);
# Create a custom namespace and the related discussion namespace:
$wgExtraNamespaces[NS_VORSTAND] = "Vorstand";
$wgExtraNamespaces[NS_VORSTAND_TALK] = "Vorstand_Diskussion";
# permission "editVorstand" required to edit the Vorstand namespace
$wgNamespaceProtection[NS_VORSTAND] = $wgNamespaceProtection[NS_VORSTAND_TALK] = array( 'vorstand' );

$wgExtraNamespaces[NS_GLOSSAR] = "Glossar";
$wgExtraNamespaces[NS_GLOSSAR_TALK] = "Glossar_Diskussion";

$wgNamespaceAliases['Kategorie'] = NS_CATEGORY;

#fix for removal of image: namesapce (we use a lot of "Bild:")
$wgNamespaceAliases['Bild'] = NS_FILE;

# Siehe auch die Seite http://www.mediawiki.org/wiki/Manual:User_rights
# sowie http://www.mediawiki.org/wiki/Manual:User_rights_management
# Prevent new user registrations except by sysops
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['autocreateaccount'] = false;
# Restrict anonymous editing
$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['*']['createpage'] = false;
$wgGroupPermissions['*']['createtalk'] = false;
$wgGroupPermissions['*']['read'] = true;

$wgGroupPermissions['user']['edit'] = false;
$wgGroupPermissions['user']['createpage'] = false;
$wgGroupPermissions['user']['createtalk'] = false;
$wgGroupPermissions['user']['read'] = true;

$wgGroupPermissions['sysop']['edit'] = true;
$wgGroupPermissions['sysop']['createpage'] = true;

$wgWhitelistAccount = array ( "sysop" => 1 );

/*******************************************************************************
 * Subpages
 ****************************************************************************
*/
# Enable subpages in the main namespace and other custom namespaces
$wgNamespacesWithSubpages[NS_MAIN] = true;
$wgNamespacesWithSubpages[NS_VORSTAND] = true;
/*******************************************************************************
 * User Registration
 ****************************************************************************
*/
# Require users to confirm email address before they can edit, true to
# enable, requires people to supply an email address when registering.
$wgEmailConfirmToEdit = true;

/*******************************************************************************
 * Upload
 ****************************************************************************
*/
# war in alter LoclSetting.php sind aber wohl defaults
#$wgUploadPath       = "$wgScriptPath/images";
#$wgUploadDirectory  = "$IP/images";
# Add new types to the existing list from DefaultSettings.php (dies sind
# 'png', 'gif', 'jpg', 'jpeg') Grafiken:
$wgFileExtensions[] = 'svg';
# Dokumente:
$wgFileExtensions[] = 'pdf';
$wgFileExtensions[] = 'doc';
$wgFileExtensions[] = 'xls';
$wgFileExtensions[] = 'docx';
$wgFileExtensions[] = 'xlsx';
$wgFileExtensions[] = 'odt';
$wgFileExtensions[] = 'odp';
$wgFileExtensions[] = 'ods';
$wgFileExtensions[] = 'odg';

$wgFileExtensions[] = 'tex';
# Archive (nicht ungefährlich!)
$wgFileExtensions[] = 'zip';
# Medien ... Video, Audio
# next three lines from old LocalSettings
$wgMathPath         = "{$wgUploadPath}/math";
$wgMathDirectory    = "{$wgUploadDirectory}/math";
$wgTmpDirectory     = "{$wgUploadDirectory}/tmp";

/** Files with these mime types will never be allowed as uploads
 * if $wgVerifyMimeType is enabled.
 * Overwrite DefaultSettings.php
 */
$wgMimeTypeBlacklist= array(
	# HTML may contain cookie-stealing JavaScript and web bugs
	'text/html', 'text/javascript', 'text/x-javascript',  'application/x-shellscript',
	# PHP scripts may execute arbitrary code on the server
	'application/x-php', 'text/x-php',
	# Other types that may be interpreted by some servers
	'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh',
	# Client-side hazards on Internet Explorer
	'text/scriptlet', 'application/x-msdownload',
	# Windows metafile, client-side vulnerability on some systems
	'application/x-msmetafile',
	# A ZIP file may be a valid Java archive containing an applet which exploits the
	# same-origin policy to steal cookies
	#'application/zip',
);

/*******************************************************************************
 * Extensions
 ****************************************************************************
*/

# zur Vorlagen-Programmierung
wfLoadExtension( 'ParserFunctions' );
# um Bilder mit Links zu versehen (und mehr)
wfLoadExtension( 'ImageMap' );

wfLoadExtension( 'Renameuser' );

//  Ajax Suche
$wgUseAjax = true;
$wgEnableMWSuggest = true;

$wgAllowRealName = false;

$wgUseRCPatrol = false;

$wgNavigationLinks = array (
			    array( 'text'=>'mainpage','href'=>'mainpage' ),
			    array( 'text'=>'recentchanges', 'href'=>'recentchanges-url' ),
			    array( 'text'=>'help', 'href'=>'helppage' ),
			    array( 'text'=>'search','href'=>'searchpage' ),
			    );

$wgCategoryMagicGallery = false;

$_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] = '0';

$wgGroupPermissions['*']['editmyprivateinfo'] = false;

$wgShowExceptionDetails = true;

