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
		'soapClientWSDL' => 'https://services.jediholo.net/tracker/service/TrackerService.php5?wsdl',
		'soapClientOptions' => array(),
	),
	
	// RPMod configuration
	'rpmod' => array(
		'soapClientWSDL' => 'https://rpmod.jediholo.net/ws/LegacyService/wsdl/v/043/',
		'soapClientOptions' => array(
			'login'		=> 'JEDI',
			'password'	=> 'n56swiupRlaQlAkI'
		),
	),
);
