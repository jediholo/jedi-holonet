<?php
class RPModServerServiceClient {

	private $client;
	private $host;
	private $port;
	private $config = array(
		'soapClientWSDL'	=> 'https://rpmod.jediholo.net/ws/ServerService/wsdl/v/051/',
		'soapClientOptions'	=> array(),
	);
	private $info = null;
	private $status = null;
	private $players = null;
	private $currentMap = null;

	/**
	 * Construct a new RPMod ServerService Client.
	 * @param string $host host name
	 * @param int $port port number
	 * @param array client configuration
	 */
	public function __construct($host, $port = 29070, $config = array()) {
		$this->config = array_merge($this->config, $config);
		$this->host = $host;
		$this->port = $port;
		$this->client = @ new SoapClient($this->config['soapClientWSDL'], $this->config['soapClientOptions']);
	}

	/**
	 * Get the server host name or IP address this client will connect to.
	 * @return string host name or IP address.
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Get the server port this client will connect to.
	 * @return integer port number.
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Get information about the server.
	 * @return array info key/value pairs as an array.
	 */
	public function getInfo() {
		// Use the cached value if available
		if ($this->info !== null) {
			return $this->info;
		}

		// Call the Web Service
		$response = $this->client->GetInfo(array('host' => $this->host, 'port' => $this->port));
		$response = $response->GetInfoResult;

		// Convert the info KeyValue array to a simple PHP array
		$this->info = array();
		foreach ($response->item as $kvp) {
			$this->info[$kvp->key] = $kvp->value;
		}

		return $this->info;
	}

	/**
	 * Get the detailed server status, including serverinfo and players.
	 * @return array serverinfo as an array of key/value pairs, and connected players as an array of Player objects.
	 */
	public function getStatus() {
		// Use the cached value if available
		if ($this->status !== null) {
			return $this->status;
		}

		// Call the Web Service
		$response = $this->client->GetStatus(array('host' => $this->host, 'port' => $this->port));
		$response = $response->GetStatusResult;

		// Init
		$this->status = array();

		// Convert the serverinfo KeyValue array to a simple PHP array
		$this->status['serverinfo'] = array();
		foreach ($response->serverInfo->item as $kvp) {
			$this->status['serverinfo'][$kvp->key] = $kvp->value;
		}

		// Keep the players array
		$this->status['players'] = array();
		if (isset($response->players->item)) {
			if (is_array($response->players->item)) {
				$this->status['players'] = $response->players->item;
			} else {
				$this->status['players'] = array($response->players->item);
			}
		}

		return $this->status;
	}

	/**
	 * Get the serverinfo.
	 * @return array serverinfo as an array of key/value pairs.
	 */
	public function getServerInfo() {
		$status = $this->getStatus();
		return $status['serverinfo'];
	}

	/**
	 * Get the connected players.
	 * @return array players as an array of Player objects containing the score, ping and name of each player.
	 */
	public function getPlayers() {
		// Use the cached value if available
		if ($this->players !== null) {
			return $this->players;
		}

		// Call the Web Service
		$response = $this->client->GetPlayers(array('host' => $this->host, 'port' => $this->port));
		$response = $response->GetPlayersResult;

		// Process the response
		$this->players = array();
		if (isset($response->item)) {
			if (is_array($response->item)) {
				$this->players = $response->item;
			} else {
				$this->players = array($response->item);
			}
		}

		return $this->players;
	}

	/**
	 * Get the current server map asset.
	 * @return object current map game asset
	 */
	public function getCurrentMap() {
		// Use the cached value if available
		if ($this->currentMap !== null) {
			return $this->currentMap;
		}

		// Call the Web Service
		$response = $this->client->GetCurrentMap(array('host' => $this->host, 'port' => $this->port));
		$response = $response->GetCurrentMapResult;

		return $response;
	}

	/**
	 * Send an Rcon command and return the result.
	 * @param string $password Rcon password
	 * @param string $command Rcon command
	 * @return string Result of the Rcon command
	 */
	public function rcon($password, $command) {
		// Call the Web Service
		$response = $this->client->Rcon(array('host' => $this->host, 'port' => $this->port, 'password' => $password, 'command' => $command));
		return $response->RconResult;
	}

}
