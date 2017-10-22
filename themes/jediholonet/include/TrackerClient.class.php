<?php
class TrackerClient {
	private $client;
	private $host;
	private $port;
	private $config = array(
		'soapClientWSDL'	=> 'https://rpmod.jediholo.net/ws/ServerService/wsdl/v/050',
		'soapClientOptions'	=> array(),
	);
	private $info = null;
	private $status = null;

	public function __construct($config, $host, $port) {
		$this->config = array_merge($this->config, $config);
		$this->host = $host;
		$this->port = $port;

		$this->client = @ new SoapClient($this->config['soapClientWSDL'], $this->config['soapClientOptions']);
	}

	/**
	 * Get the Q3 server host name or IP address this client will connect to.
	 * @return host name or IP address.
	 */
	public function getHost() {
		return $this->host;
	}

	/**
	 * Get the Q3 server port this client will connect to.
	 * @return port number.
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Get information about the server.
	 * @return info key/value pairs as an array.
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
	 * @return array containing the serverinfo as an array of key/value pairs, and the connected players as an array of Player objects.
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

 	/* Get the serverinfo.
	 * This calls getStatus() if necessary and only returns the serverinfo.
	 * @return serverinfo as an array of key/value pairs.
	 */
	public function getServerInfo() {
		if ($this->status === null) $this->getStatus();
		return $this->status['serverinfo'];
	}

	/**
	 * Get the connected players.
	 * This calls getStatus() if necessary and only returns the connected players.
	 * @return players as an array of Player objects containing the score, ping and name of each player.
	 */
	public function getPlayers() {
		if ($this->status === null) $this->getStatus();
		return $this->status['players'];
	}

	/**
	 * Send an Rcon command and return the result.
	 * @param $password Rcon password.
	 * @param $command Rcon command.
	 */
	public function rcon($password, $command) {
		// Simply call the Web Service
		$response = $this->client->Rcon(array('host' => $this->host, 'port' => $this->port, 'password' => $password, 'command' => $command));
		return $response->RconResult;
	}
}
