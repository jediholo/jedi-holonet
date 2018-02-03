<?php
require_once(TEMPLATEPATH . '/include/RPMod.inc.php');
require_once(TEMPLATEPATH . '/include/ClassParser.class.php');
require_once(TEMPLATEPATH . '/include/FPCParser.class.php');

function printAccountInfo($username) {
	try {
		$RPModClient = @ new SoapClient($GLOBALS['JEDI_config']['rpmod']['soapClientWSDL'], $GLOBALS['JEDI_config']['rpmod']['soapClientOptions']);
		$classParser = new ClassParser(TEMPLATEPATH . '/include/classes.dat');
		$fpcParser = new FPCParser(TEMPLATEPATH . '/include/forcecost.dat');
		$forcePowerCost = $fpcParser->getFPC();

		// Get the Account data
		$response = $RPModClient->GetAccountData($username);

		// Create the Account array from the response
		$account = array();
		foreach ($response as $currentpair) {
			$account[$currentpair->key] = $currentpair->value;
		}

		// Complete account data with default values if they are missing
		if (!isset($account['xp']))
			$account['xp'] = 0;
		if (!isset($account['rank']))
			$account['rank'] = 0;
		if (!isset($account['adminRank']))
			$account['adminRank'] = 0;
		if (!isset($account['fullName']))
			$account['fullName'] = '';
		if (!isset($account['className']))
			$account['className'] = '';
		if (!isset($account['forceBonds']))
			$account['forceBonds'] = '';
		if (!isset($account['forceTemplateMax']))
			$account['forceTemplateMax'] = '';

		// Determine the reference Force Template
		if ($GLOBALS['rpmod_config']['lockTemplate']) {
			$refTemplate = $account['forceTemplateMax'];
		} else {
			$refTemplate = $account['forceTemplate'];
		}

		// Account Info beginning
		echo '<p>';

		// Class
		echo '<strong>Class:</strong> ';
		if (empty($account['className'])) {
			echo ' <span class="error">none</span>';
		} else {
			echo $account['className'];
			if (is_object($classParser) && !$classParser->classExists($account['className'])) {
				echo ' <span class="error">(invalid)</span>';
			}
		}
		echo "<br />\n";

		// XP and points remaining (if available)
		echo '<strong>XP:</strong> ';
		if ($refTemplate && strlen($refTemplate) >= NUM_FORCE_POWERS) {
			$usedPoints = 0;
			for ($i = 0; $i < NUM_FORCE_POWERS_EXT; $i++) {
				if (isset($refTemplate[$i])) {
					for ($powerLevel = 0; $powerLevel < $refTemplate[$i]; $powerLevel++) {
						$usedPoints += $forcePowerCost[$i][$powerLevel];
					}
				}
			}
			$unusedPoints = intval($account['xp']) - $usedPoints;
			echo intval($account['xp']) . ' (' . $usedPoints . ' used, ' . $unusedPoints . ' remaining)';
		} else {
			echo intval($account['xp']);
		}
		echo "<br />\n";

		// Level (Class Parser needed)
		if (is_object($classParser)) {
			echo '<strong>Level:</strong> ';
			if ($classParser->classExists($account['className'])) {
				// Get level information once and for all the script
				$curLevel = $classParser->getLevelFromXP($account['className'], $account['xp']);
				$curLevelNum = $classParser->getLevelNumFromXP($account['className'], $account['xp']);
				$nextLevel = $classParser->getLevelFromNum($account['className'], $curLevelNum+1);

				// Current level
				echo $curLevelNum;

				// Next level (if any)
				echo "<br />\n";
				echo '<strong>Next level:</strong> ';

				if (isset($nextLevel) && isset($nextLevel['minXP'])) {
					echo 'level ' . ($curLevelNum+1) . ' with ' . $nextLevel['minXP'] . ' XP (+' . ($nextLevel['minXP'] - $account['xp']) . ' XP)';
				} else {
					echo 'none';
				}

			} else {
				echo '<span class="error">none</span>';
			}
			echo "<br />\n";
		}

		// Rank and AdminRank
		echo '<strong>Rank:</strong> ' . $GLOBALS['rpmod_config']['ranks'][intval($account['rank'])] . "<br />\n";
		echo '<strong>AdminRank:</strong> ' . $GLOBALS['rpmod_config']['adminRanks'][intval($account['adminRank'])] . "<br />\n";

		// Model Scale
		echo '<strong>Model scale:</strong> ';
		if ($account['modelScale']) {
			echo  $account['modelScale'] . '%';
		} else {
			echo 'default';
		}
		echo "<br />\n";

		// Force bonds
		echo '<strong>Force bonds:</strong> ';
		if ($account['forceBonds']) {
			echo 'with ';
			$bonds = explode('|', $account['forceBonds']);
			for ($i = 0; $i < count($bonds); $i++) {
				if ($i != 0) echo ', ';
				echo $bonds[$i];
			}
		} else {
			echo 'none';
		}
		echo "<br />\n";

		// Force regen time (level & class parser needed)
		if (isset($curLevel) && isset($curLevel['forceRegenTime'])) {
			echo '<strong>Force regeneration time:</strong> ' . $curLevel['forceRegenTime'] . ' ms';
		}

		echo "</p>\n";

		// Last Force Template
		echo "<p><strong>Force Template:</strong></p>\n";
		if ($refTemplate) {
			echo "<table class=\"ft_table\">\n";
			foreach ($GLOBALS['rpmod_config']['orderedPowers'] as $power) {
				if ($power == -1) {
					// Blank line
					echo "  <tr><td colspan=\"7\"><p></p></td></tr>\n";
				} else {
					$curPoints = 0;
					$totalPoints = 0;
					$curUnusedPoints = $unusedPoints;

					echo "  <tr>\n";
					echo '    <th class="ft_title">' . $GLOBALS['rpmod_config']['forcePowerNames'][$power] . ":</th>\n";

					for ($powerLevel = 0; $powerLevel < 5; $powerLevel++) {
						if ($GLOBALS['rpmod_config']['restrictForce'] && isset($curLevel) && isset($curLevel['forceTemplateMax']) && (!isset($curLevel['forceTemplateMax'][$power]) || $powerLevel >= $curLevel['forceTemplateMax'][$power])) {
							// Class-restricted
							echo '    <td class="ft_disabled">[' . $forcePowerCost[$power][$powerLevel] . "]</td>\n";
						} else if ($GLOBALS['rpmod_config']['lockTemplate'] && (!isset($account['forceTemplateMax'][$power]) || $powerLevel >= $account['forceTemplateMax'][$power])) {
							// New power
							if ($forcePowerCost[$power][$powerLevel] <= $curUnusedPoints) {
								// We can buy this one
								echo '    <td class="ft_new">[' . $forcePowerCost[$power][$powerLevel] . "]</td>\n";
								$curUnusedPoints -= $forcePowerCost[$power][$powerLevel];
							} else {
								// Unavailable for the moment
								echo '    <td class="ft_unavailable">[' . $forcePowerCost[$power][$powerLevel] . "]</td>\n";
							}
						} else {
							// Known power
							if (isset($account['forceTemplate'][$power]) && $powerLevel < $account['forceTemplate'][$power]) {
								// Selected one
								echo '    <td class="ft_on">[' . $forcePowerCost[$power][$powerLevel] . "]</td>\n";
								$curPoints += $forcePowerCost[$power][$powerLevel];
							} else {
								// Not selected but available
								echo '    <td class="ft_off">[' . $forcePowerCost[$power][$powerLevel] . "]</td>\n";
								if ($GLOBALS['rpmod_config']['lockTemplate']) {
									$curPoints += $forcePowerCost[$power][$powerLevel];
								}
							}
						}
						$totalPoints += $forcePowerCost[$power][$powerLevel];
					}

					// Used points / total points for this power
					echo '    <td class="ft_points">(' . $curPoints . ' / ' . $totalPoints . ")</td>\n";
					echo "  </tr>\n";
				}
			}
			echo "</table>\n";
		} else {
			echo "<p>None stored.</p>\n";
		}

	} catch (Exception $e) {
		echo "<p><strong>ERROR:</strong> {$e->getMessage()}</p>\n";
	}
}
