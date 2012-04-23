<?php
/**
 * ::JEDI:: Web Site
 * @file Configuration file.
 * @author Fabien Crespel
 */

$GLOBALS['JEDI_config'] = array(
	'numSidebars' => 5,

	// Server tracker configuration
	'tracker' => array(
		'soapClientWSDL' => 'http://services.jediholo.net/tracker/service/TrackerService.php5?wsdl',
		'soapClientOptions' => array(),
	),
	
	// RPMod configuration
	'rpmod' => array(
		'soapClientWSDL' => 'http://rpmod.jediholo.net/service/RPModService.php5?wsdl',
		'soapClientOptions' => array(
			'login'		=> 'JEDI',
			'password'	=> 'n56swiupRlaQlAkI'
		),
	),
);
