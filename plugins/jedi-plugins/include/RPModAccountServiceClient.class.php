<?php
class RPModAccountServiceClient {

	private $client;
	private $config = array(
		'soapClientWSDL'	=> 'https://rpmod.jediholo.net/ws/AccountService/wsdl/v/051/',
		'soapClientOptions'	=> array(),
	);

	/**
	 * Construct a new RPMod AccountService Client.
	 * @param array $config client configuration
	 */
	public function __construct($config = array()) {
		$this->config = array_merge($this->config, $config);
		$this->client = @ new SoapClient($this->config['soapClientWSDL'], $this->config['soapClientOptions']);
	}

	/**
	 * Get a User object from its user name.
	 * @param string $userName Unique name of the user to look for
	 * @return object User object
	 */
	public function getUser($userName) {
		// Call the Web Service
		$response = $this->client->GetUser(array('userName' => $userName));
		$response = $response->GetUserResult;
		return $response;
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
		$response = $response->GetUsersResult;

		// Process the response
		$users = array();
		if (isset($response->item)) {
			if (is_array($response->item)) {
				$users = $response->item;
			} else {
				$users = array($response->item);
			}
		}

		return $users;
	}

}
