<?php
require_once(__DIR__ . '/JsonRpcClient.class.php');

class RPModAccountServiceClient {

	private $client;
	private $config = array(
		'url' 		=> 'https://rpmod.jediholo.net/ws/AccountService/jsonrpc/v/051',
		'headers'	=> array(),
	);

	/**
	 * Construct a new RPMod AccountService Client.
	 * @param array $config client configuration
	 */
	public function __construct($config = array()) {
		$this->config = array_merge($this->config, $config);
		$this->client = new JsonRpcClient($this->config['url'], $this->config['headers']);
	}

	/**
	 * Get a User object from its user name.
	 * @param string $userName Unique name of the user to look for
	 * @return object User object
	 */
	public function getUser($userName) {
		// Call the Web Service
		return $this->client->GetUser(array('userName' => $userName));
	}

	/**
	 * Get User objects from the database.
	 * @param int $offset position to start from
	 * @param int $limit number of User objects to return (max 100)
	 * @param string $order sort order as '<field> [ASC|DESC]'
	 * @param string $filter custom filter as '<field> <operator> <value>[, <field> <operator> <value> ...]'
	 * @return object[] User objects
	 */
	public function getUsers($offset = 0, $limit = 10, $order = 'userName', $filter = '') {
		// Call the Web Service
		$response = $this->client->GetUsers(array('offset' => $offset, 'limit' => $limit, 'order' => $order, 'filter' => $filter));

		// Process the response
		$users = array();
		if (isset($response)) {
			if (is_array($response)) {
				$users = $response;
			} else {
				$users = array($response);
			}
		}

		return $users;
	}

}
