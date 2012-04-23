<?php
class ClassParser {
	private $classes = array();

	// Class constructor
	function __construct($filename) {
		$this->parseFile($filename);
	}
	
	// Parse the Classes file
	function parseFile($filename) {
		$handle = @fopen($filename, "r");
		if (!$handle) {
			throw new Exception('Error opening the ' . $filename . ' file.', ERROR_CLASSPARSER);
		}
		
		$this->classes = array();
		$depth = 0;
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
				
				// Handle sub-sections
				if ($token == '{') {
					$depth++;
					if ($depth == 1) {
						// New class
						if (!isset($currentClassName)) {
							throw new Exception('Parse error: missing class name.', ERROR_CLASSPARSER);
						}
						$currentClass = array(
							'name' => $currentClassName,
							'description' => '',
							'primaryPowers' => '',
							'secondaryPowers' => '',
							'levels' => array()
						);
						$currentLevelNum = 0;
					} else if ($depth == 2) {
						// New level
						$currentLevelNum++;
						$currentLevel = array();
					}
					// Skip the whole line
					break;
				} else if ($token == '}') {
					$depth--;
					if ($depth == 0) {
						// End of class
						if (count($currentClass['levels']) == 0) {
							// No levels => invalid
							throw new Exception("Parse error: no levels for class '{$currentClassName}'.", ERROR_CLASSPARSER);
						}
						$this->classes[$currentClassName] = $currentClass;
						unset($currentClassName, $currentClass);
					} else if ($depth == 1) {
						// End of level
						if (!array_key_exists('minXP', $currentLevel)) {
							// No minXP => invalid
							throw new Exception("Parse error: no minXP for level #{$currentLevelNum} in class '{$currentClassName}'.", ERROR_CLASSPARSER);
						}
						$currentClass['levels'][$currentLevelNum] = $currentLevel;
						unset($currentLevel);
					}
					// Skip the whole line
					break;
				}
				
				if ($depth == 0) {
					// Not in a class: remember the supposed class name
					$currentClassName = $token;
					
				} else if ($depth == 1) {
					// We are in a class definition: fill the array
					$value = strtok(" \t\n\r");
					if ($value !== false && substr($value, 0, 1) == '"') {
						if (substr($value, strlen($value) - 1) == '"')
							$value = substr($value, 1, strlen($value) - 2 );
						else
							$value = substr($value, 1) . ' ' . strtok("\"\n\r");
					}
					$currentClass[$token] = $value;
					
				} else if ($depth == 2) {
					// We are in a level definition: fill the array
					$currentLevel[$token] = strtok(" \t\n\r");
				}
				
				// Get the next token
				$token = strtok(" \t\n\r");
			}
		}
		
		fclose($handle);
	}
	
	// Tell whether a class exists
	function classExists($className) {
		return array_key_exists($className, $this->classes);
	}
	
	// Get the whole classes array
	function getClasses() {
		return $this->classes;
	}
	
	// Get the number of classes loaded
	function getClassesNum() {
		return count($this->classes);
	}
	
	// Get the names of all classes loaded
	function getClassesNames() {
		return array_keys($this->classes);
	}
	
	// Get a single class
	function getClass($className) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			return $this->classes[$className];
		}
	}
	
	// Get the whole levels array for a given class
	function getLevels($className) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			return $this->classes[$className]['levels'];
		}
	}
	
	// Get the number of levels for a given class
	function getLevelsNum($className) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			return count($this->classes[$className]['levels']);
		}
	}
	
	// Get a single level's array for a given class, depending on its number
	function getLevelFromNum($className, $num) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			if (!array_key_exists($num, $this->classes[$className]['levels'])) {
				return NULL;
				//throw new Exception("Level #{$num} in class '{$className}' doesn't exist.", ERROR_CLASSPARSER);
			} else {
				return $this->classes[$className]['levels'][$num];
			}
		}
	}
	
	// Get a single level's array for a given class, depending on the amount of XP
	function getLevelFromXP($className, $XP) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			for($i = count($this->classes[$className]['levels']); $i > 0; $i--) {
				if ($XP >= $this->classes[$className]['levels'][$i]['minXP']) {
					return $this->classes[$className]['levels'][$i];
				}
			}
			return NULL;
		}
	}
	
	// Get a single level's number for a given class, depending on the amount of XP
	function getLevelNumFromXP($className, $XP) {
		if (!$this->classExists($className)) {
			throw new Exception("The class named '{$className}' doesn't exist.", ERROR_CLASSPARSER);
		} else {
			for($i = count($this->classes[$className]['levels']); $i > 0; $i--) {
				if ($XP >= $this->classes[$className]['levels'][$i]['minXP']) {
					return $i;
				}
			}
			return 0;
		}
	}
}
