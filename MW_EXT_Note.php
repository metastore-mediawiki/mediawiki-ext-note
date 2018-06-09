<?php

// Confirm MediaWiki environment.
if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension and thus not a valid entry point.' );
}

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'MW_EXT_Note' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgExtensionMessagesFiles['MW_EXT_Note'] = __DIR__ . '/i18n';

	/* wfWarn(
	  'Deprecated PHP entry point used for MW_EXT_Note extension. Please use wfLoadExtension instead, ' .
	  'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	); */

	return;
} else {
	die( 'This version of the MW_EXT_Note extension requires MediaWiki 1.25+' );
}
