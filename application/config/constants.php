<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| My Constants
|--------------------------------------------------------------------------
*/

$doc_root = dirname(__FILE__);

define("ROOT", "https://portal.fhpi.com.ph/irs");
define("WEB", ROOT);
define("JSCRIPT", WEB."/js");
define("JS", WEB."/script");
define("CSS", WEB."/css");
define("UFILE", WEB."/files");
define("LIB_WEB", WEB."/lib");
define("CLASSES_WEB", WEB."/lib/class");	
define("IMG_WEB", WEB."/images");
define("REQUEST_WEB", WEB."/requests");

define("DOCUMENT", $doc_root);
define("LIB", DOCUMENT."/lib");
define("CLASSES", DOCUMENT."/lib/class");
define("IMG", DOCUMENT."/images");
define("FILES_DIR", DOCUMENT."/images/files");	
define("REQUEST", DOCUMENT."/requests");
define("OBJROOT", DOCUMENT."/objects");

define("SESSION_NAME", "fhpiv2_user");

define("SITENAME", "FHPI iRS System v2");
define("SYSTEMNAME", "ONLINE REQUISITION SYSTEM (iRS) v2");

# PAGINATION
define("QS_VAR", "page"); // the variable name inside the query string (don't use this name inside other links)
define("STR_FWD", "&gt;"); // the string is used for a link (step forward)
define("STR_BWD", "&lt;"); // the string is used for a link (step backward)
define("NUM_LINKS", 5); // the number of links inside the navigation (the default value)

#MAIL
define("SMTP", "jisleta");
define("SMTP_PORT", "25");
define("SENDMAIL_FROM", "");

/* End of file constants.php */
/* Location: ./application/config/constants.php */