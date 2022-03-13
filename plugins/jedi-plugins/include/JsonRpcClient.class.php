<?php
class JsonRpcClient {

	private $url;
	private $headers;

	/**
	 * Construct a new JSON-RPC client.
	 * @param string $url Service URL
	 * @param array $headers HTTP headers to send (optional)
	 */
	public function __construct($url, $headers = array()) {
		$this->url = $url;
		$this->headers = $headers;
	}

	/**
	 * Call the JSON-RPC service (magic method overload).
	 * @param string $name Method name (RPC method to call)
	 * @param array $arguments Method arguments (first argument should be parameters to send)
	 * @return object|array RPC method result
	 */
	public function __call($name, $arguments) {
		return $this->call($name, isset($arguments[0]) ? $arguments[0] : array());
	}

	/**
	 * Call the JSON-RPC service.
	 * @param string $method RPC method to call
	 * @param array $params Parameters to send (optional)
	 * @return object|array RPC method result
	 */
	public function call($method, $params = array()) {
		// Prepare request
		$req = array(
			'jsonrpc' => '2.0',
			'id' => 'Wordpress',
			'method' => $method,
			'params' => $params,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($req));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($this->headers, array('Accept: application/json', 'Content-Type: application/json')));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		// Execute request
		$result = curl_exec($curl);
		$errno = curl_errno($curl);
		curl_close($curl);

		// Check response
		if ($result === false) {
			throw new Exception('Failed to connect to service (code: ' . $errno . ')');
		} else if (strlen($result) == 0) {
			throw new Exception('Empty response from service');
		} else if ($result[0] != '{') {
			throw new Exception('Unexpected response from service');
		}

		// Parse response
		$resp = json_decode($result);
		if (isset($resp->error)) {
			if (isset($resp->error->message)) {
				throw new Exception($resp->error->message);
			} else if (isset($resp->error->code)) {
				throw new Exception('Unknown error (code: ' . $resp->error->code . ')');
			} else {
				throw new Exception('Unknown error');
			}
		}

		// Return result
		return isset($resp->result) ? $resp->result : null;
	}

}
