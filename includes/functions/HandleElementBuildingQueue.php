<?php
/**
 * Tis file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

function HandleElementBuildingQueue ( $CurrentUser, &$CurrentPlanet, $ProductionTime ) {
	global $resource;
	// Pendant qu'on y est, si on verifiait ce qui se passe dans la queue de construction du chantier ?
	if ($CurrentPlanet['b_hangar_id'] != 0) {
		$Builded                    = array ();
		$CurrentPlanet['b_hangar'] += $ProductionTime;

		$BuildQueue                 = explode(';', $CurrentPlanet['b_hangar_id']);

		foreach ($BuildQueue as $Node => $Array) {
			if ($Array != '') {
				$Item              = explode(',', $Array);
				// On stocke sous forme Element, Nombre, Duree de fab
				$BuildArray[$Node] = array($Item[0], $Item[1], GetBuildingTime ($CurrentUser, $CurrentPlanet, $Item[0]));
			}
		}

		$CurrentPlanet['b_hangar_id'] = '';

		$UnFinished = false;
		foreach ( $BuildArray as $Node => $Item ) {
			if (!$UnFinished) {
				$Element   = $Item[0];
				$Count     = $Item[1];
				$BuildTime = $Item[2];
				while ( $CurrentPlanet['b_hangar'] >= $BuildTime && !$UnFinished ) {
					if ( $Count > 0 ) {
						$CurrentPlanet['b_hangar'] -= $BuildTime;
						$Builded[$Element]++;
						$CurrentPlanet[$resource[$Element]]++;
						$Count--;
						if ($Count == 0) {
							break;
						}
					} else {
						$UnFinished = true;
						break;
					}
				}
			}
			if ( $Count != 0 ) {
				$CurrentPlanet['b_hangar_id'] .= $Element.",".$Count.";";
			}
		}
	} else {
		$Builded                   = '';
		$CurrentPlanet['b_hangar'] = 0;
	}

	return $Builded;
}
?>