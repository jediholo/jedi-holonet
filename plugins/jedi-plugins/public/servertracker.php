<?php
require_once(__DIR__ . '/../include/RPModServerServiceClient.class.php');

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	die("This page must be downloaded from an XMLHttpRequest object (AJAX).");
}

try {
	// Check input
	if (empty($_POST['server'])) {
		throw new Exception('No server specified');
	}

	// Get server host:port
	$server = explode(':', $_POST['server']);
	if (count($server) != 2 || !is_numeric($server[1])) {
		throw new Exception('Invalid server specified (host:port expected)');
	}

	// Display server host:port
	echo "<li><dl><dt>Server: </dt><dd>{$server[0]}:{$server[1]}</dd></dl></li>\n";

	// Get live server status
	$trackerClient = new RPModServerServiceClient($server[0], $server[1]);
	$serverinfo = $trackerClient->getServerInfo();
	$currentMap = $trackerClient->getCurrentMap();
	$players = $trackerClient->getPlayers();
	$numClients = count($players);

	// Display map section
	$mapSection = $serverinfo['mapname'];
	if ($currentMap != null && isset($currentMap->slug)) {
		$mapSection = '<a href="//rpmod.jediholo.net/gameasset/view/name/' . $currentMap->slug . '">' . $mapSection . '</a>';
	}
	echo "<li><dl><dt>Map: </dt><dd>{$mapSection}</dd></dl></li>\n";

	// Display players section
	$playersSection = "{$numClients}/{$serverinfo['sv_maxclients']}";
	if ($numClients > 0) {
		$id = uniqid();
		$playersSection = "<a href=\"#\" onclick=\"slideToggle('{$id}-players'); return false;\">{$playersSection}</a>";
		$playersSection .= "<div class=\"box players\" style=\"display: none;\" id=\"{$id}-players\"><ul>\n";
		foreach ($players as $player) {
			$playersSection .= '<li>';
			if (isset($player->account)) {
				$playersSection .= "<a href=\"//rpmod.jediholo.net/character/view/userName/{$player->account}\">";
			}
			$playersSection .= "<span title=\"{$player->sanitizedName}\">{$player->colorizedName}</span>";
			if (isset($player->account)) {
				$playersSection .= '</a>';
			}
			$playersSection .= "</li>\n";
		}
		$playersSection .= "</ul></div>\n";
	}
	echo "<li><dl><dt>Players: </dt><dd>{$playersSection}</dd></dl></li>\n";

} catch (Exception $e) {
	echo "<li><strong>{$e->getMessage()}</strong></li>\n";
}
