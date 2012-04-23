<?php
class FPCParser {
	private $forcePowerCost = array();

	// Class constructor
	function __construct($filename) {
		$this->parseFile($filename);
	}
	
	// Parse the ForcePowerCost file
	function parseFile($filename) {
		$handle = @fopen($filename, 'r');
		if (!$handle) {
			throw new Exception('Error opening the ' . $filename . ' file.', ERROR_FPCPARSER);
		}
		
		$this->forcePowerCost = $GLOBALS['rpmod_config']['defaultForcePowerCost'];
		while (!feof($handle)) {
			// Read a whole line
			$buffer = fgets($handle);
			
			// Filter comments
			$posComments = strpos($buffer, '//');
			if ($posComments !== false) {
				$buffer = substr($buffer, 0, $posComments);
			}
			
			// Filter empty lines
			if (trim($buffer) == '') {
				continue;
			}
			
			// Tokenize the line
			$token = strtok($buffer," \t\n\r");
			while ($token !== false) {
				
				if (!defined($token)) {
					throw new Exception("Parse error: unknown Force power '{$token}'.", ERROR_FPCPARSER);
				}
				
				$tmpFPC = explode('|', strtok(" \t\n\r"));
				$tmpFPC = array_pad($tmpFPC, 5, 0);
				for ($i = 0; $i < 5; $i++) {
					if ($tmpFPC[$i] < 0 || $tmpFPC[$i] > 99) {
						throw new Exception('Parse error: the amount of points for level ' . ($i+1) . " of Force power '{$token}' is invalid.", ERROR_FPCPARSER);
					}
				}
				
				$this->forcePowerCost[constant($token)] = $tmpFPC;
				
				// Get the next token
				$token = strtok(" \t\n\r");
			}
		}
		
		fclose($handle);
	}
	
	// Get the whole ForcePowerCost array
	function getFPC() {
		return $this->forcePowerCost;
	}
}
