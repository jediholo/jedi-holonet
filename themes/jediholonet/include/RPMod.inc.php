<?php
// Force Power constants
define('FP_HEAL',		0);
define('FP_LEVITATION',		1);
define('FP_SPEED',		2);
define('FP_PUSH',		3);
define('FP_PULL',		4);
define('FP_TELEPATHY',		5);
define('FP_GRIP',		6);
define('FP_LIGHTNING',		7);
define('FP_RAGE',		8);
define('FP_PROTECT',		9);
define('FP_ABSORB',		10);
define('FP_TEAM_HEAL',		11);
define('FP_TEAM_FORCE',		12);
define('FP_DRAIN',		13);
define('FP_SEE',		14);
define('FP_SABER_OFFENSE',	15);
define('FP_SABER_DEFENSE',	16);
define('FP_SABERTHROW',		17);
define('NUM_FORCE_POWERS',	18);

// Error codes
define('ERROR_SOAP',		1);
define('ERROR_AUTH',		2);
define('ERROR_CLASSPARSER',	3);
define('ERROR_FPCPARSER',	4);
define('ERROR_PERMISSION', 	5);
define('ERROR_PARAMETER',	6);

// Permissions constants
define('PERMISSION_CREATE',	1);
define('PERMISSION_SET',	2);
define('PERMISSION_GIVEXP',	4);
define('PERMISSION_DELETE',	8);

$GLOBALS['rpmod_config'] = array(
	// Files to parse
	'parseClasses'		=> true,
	'parseForcePowerCost'	=> true,
	
	// Account config
	'restrictForce'		=> true,
	'lockTemplate'		=> true,

	// RP ranks
	'ranks'			=> array (
		'Guest',
		'Initiate',
		'Padawan',
		'Knight',
		'Master',
		'Council member'
	),
	
	// Admin ranks
	'adminRanks'		=> array (
		'None',
		'Instructor',
		'Watchman',
		'Mission Director',
		'AdminRank4',
		'Council',
		'God'
	),

	// Force power names
	'forcePowerNames'	=> array (
		FP_LEVITATION		=> 'Jump',
		FP_PUSH			=> 'Push',
		FP_PULL			=> 'Pull',
		FP_SPEED		=> 'Speed',
		FP_SEE			=> 'Seeing',
		
		FP_ABSORB		=> 'Absorb',
		FP_HEAL			=> 'Heal',
		FP_PROTECT		=> 'Protect',
		FP_TELEPATHY		=> 'Mind trick',
		FP_TEAM_HEAL		=> 'Heal others',
		
		FP_GRIP			=> 'Hold',
		FP_DRAIN		=> 'Slow',
		FP_LIGHTNING		=> 'Storm',
		FP_RAGE			=> 'Fury',
		FP_TEAM_FORCE		=> 'Force Meld',
		
		FP_SABER_OFFENSE	=> 'Saber Offense',
		FP_SABER_DEFENSE	=> 'Saber Defense',
		FP_SABERTHROW		=> 'Saber Throw'
	),
	
	// Default Force Power Cost
	'defaultForcePowerCost'	=> array (
		FP_LEVITATION		=> array( 0, 2, 6, 8, 10 ),	// Jump
		FP_PUSH			=> array( 1, 3, 6, 8, 10 ),	// Push
		FP_PULL			=> array( 1, 3, 6, 8, 10 ),	// Pull
		FP_SPEED		=> array( 2, 4, 6, 8, 10 ),	// Speed
		FP_SEE			=> array( 2, 5, 8, 10, 12 ),	// Seeing
		
		FP_ABSORB		=> array( 1, 3, 6, 8, 10 ),	// Absorb
		FP_HEAL			=> array( 2, 4, 6, 8, 10 ),	// Heal
		FP_PROTECT		=> array( 2, 5, 8, 10, 12 ),	// Protect
		FP_TELEPATHY		=> array( 4, 6, 8, 10, 12 ),	// Mind trick
		FP_TEAM_HEAL		=> array( 1, 3, 6, 8, 10 ),	// Team Heal (Heal others)
		
		FP_GRIP			=> array( 1, 3, 6, 8, 10 ),	// Grip (Hold)
		FP_DRAIN		=> array( 2, 4, 6, 8, 10 ),	// Drain (Slow)
		FP_LIGHTNING		=> array( 2, 5, 8, 10, 12 ),	// Lightning (Storm)
		FP_RAGE			=> array( 4, 6, 8, 10, 12 ),	// Rage (Furry)
		FP_TEAM_FORCE		=> array( 1, 3, 6, 8, 10 ),	// Team Energize (Force meld)
		
		FP_SABER_OFFENSE	=> array( 1, 5, 8, 10, 12 ),	// Saber Offense
		FP_SABER_DEFENSE	=> array( 1, 5, 8, 10, 12 ),	// Saber Defense
		FP_SABERTHROW		=> array( 4, 6, 8, 10, 12 )	// Saber throw
	),
	
	// Helper array to print the ordered list of powers in the Force Template
	'orderedPowers'		=> array (
		FP_LEVITATION,		// Jump
		FP_PUSH,		// Push
		FP_PULL,		// Pull
		FP_SPEED,		// Speed
		FP_SEE,			// Seeing
		-1,			// --- Space -----------------------------
		FP_ABSORB,		// Absorb
		FP_HEAL,		// Heal
		FP_PROTECT,		// Protect
		FP_TELEPATHY,		// Mind trick
		FP_TEAM_HEAL,		// Team Heal (Heal others)
		-1,			// --- Space -----------------------------
		FP_GRIP,		// Grip (Hold)
		FP_DRAIN,		// Drain (Slow)
		FP_LIGHTNING,		// Lightning (Storm)
		FP_RAGE,		// Rage (Furry)
		FP_TEAM_FORCE,		// Team Energize (Force meld)
		-1,			// --- Space -----------------------------
		FP_SABER_OFFENSE,	// Saber Offense
		FP_SABER_DEFENSE,	// Saber Defense
		FP_SABERTHROW		// Saber throw
	),

);
