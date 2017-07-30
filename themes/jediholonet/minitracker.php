<?php
require_once('include/config.inc.php');
require_once('include/TrackerClient.class.php');

function isAjax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

if (!isAjax()) {
	die("This page must be downloaded from an XMLHttpRequest object (AJAX).");
}

try {
	if (empty($_POST['server'])) {
		throw new Exception('No server specified');
	}
	
	$server = explode(':', $_POST['server']);
	
	if (count($server) != 2 || !is_numeric($server[1])) {
		throw new Exception('Invalid server specified (host:port expected)');
	}
	
	echo "<li><dl><dt>IP: </dt><dd>{$server[0]}:{$server[1]}</dd></dl></li>\n";
	
	$trackerClient = new TrackerClient($GLOBALS['JEDI_config']['tracker'], $server[0], $server[1]);
	$serverinfo = $trackerClient->getServerInfo();
	$players = $trackerClient->getPlayers();
	$numClients = count($players);
	
	echo "<li><dl><dt>Map: </dt><dd>{$serverinfo['mapname']}</dd></dl></li>\n";
	
	$playersSection = "{$numClients}/{$serverinfo['sv_maxclients']}";
	if ($numClients > 0) {
		$id = uniqid();
		$playersSection = "<a href=\"#\" onclick=\"slideToggle('{$id}-players'); return false;\">{$playersSection}</a>";
		$playersSection .= "<div class=\"box\" style=\"display: none;\" id=\"{$id}-players\"><ul>\n";
		foreach ($players as $player) {
			$playersSection .= "<li>{$player->colorizedName}</li>\n";
		}
		$playersSection .= "</ul></div>";
	}
	echo "<li><dl><dt>Players: </dt><dd>{$playersSection}</dd></dl></li>\n";

} catch (Exception $e) {
	echo "<li><strong>{$e->getMessage()}</strong></li>\n";
}
