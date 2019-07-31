<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogics = blea::byType('blea');
?>

<table class="table table-condensed tablesorter" id="table_healthopenenocean">
	<thead>
		<tr>
			<th>{{Image}}</th>
			<th>{{Module}}</th>
			<th>{{ID}}</th>
			<th>{{Mac}}</th>
			<th>{{Type}}</th>
			<th>{{Statut}}</th>
			<th>{{Batterie}}</th>
			<th>{{Rssi}}</th>
			<th>{{Antenne Émission}}</th>
			<th>{{Antenne Réception}}</th>
			<th>{{Présent}}</th>
			<th>{{Rafraîchissement forcé}}</th>
			<th>{{Dernière communication}}</th>
			<th>{{Date création}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	$alternateImg = $eqLogic->getConfiguration('iconModel');
	if (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $alternateImg . '.jpg')) {
		$img = '<img class="lazy" src="plugins/blea/core/config/devices/' . $alternateImg . '.jpg" height="55" width="55" style="' . $opacity . '"/>';
	} elseif (file_exists(dirname(__FILE__) . '/../../core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg')) {
		$img = '<img class="lazy" src="plugins/blea/core/config/devices/' . $eqLogic->getConfiguration('device') . '.jpg" height="55" width="55" style="' . $opacity . '"/>';
	} else {
		$img = '<img class="lazy" src="plugins/blea/doc/images/blea_icon.png" height="55" width="55" style="' . $opacity . '"/>';
	}
	echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getId() . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getLogicalId() . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em;cursor:default;">' . $eqLogic->getConfiguration('iconModel') . '</span></td>';
	$status = '<span class="label label-success" style="font-size : 1em;cursor:default;">{{OK}}</span>';
	if ($eqLogic->getStatus('state') == 'nok') {
		$status = '<span class="label label-danger" style="font-size : 1em;cursor:default;">{{NOK}}</span>';
	}
	echo '<td>' . $status . '</td>';
	$battery_status = '<span class="label label-success" style="font-size : 1em;">{{OK}}</span>';
	if ($eqLogic->getStatus('battery') < 20 && $eqLogic->getStatus('battery') != '') {
		$battery_status = '<span class="label label-danger" style="font-size : 1em;">' . $eqLogic->getStatus('battery') . '%</span>';
	} elseif ($eqLogic->getStatus('battery') < 60 && $eqLogic->getStatus('battery') != '') {
		$battery_status = '<span class="label label-warning" style="font-size : 1em;">' . $eqLogic->getStatus('battery') . '%</span>';
	} elseif ($eqLogic->getStatus('battery') > 60 && $eqLogic->getStatus('battery') != '') {
		$battery_status = '<span class="label label-success" style="font-size : 1em;">' . $eqLogic->getStatus('battery') . '%</span>';
	} else {
		$battery_status = '<span class="label label-primary" style="font-size : 1em;" title="{{Secteur}}"><i class="fa fa-plug"></i></span>';
	}
	echo '<td>' . $battery_status . '</td>';
	$rssi ='';
	$remotes = blea_remote::all();
	foreach ($remotes as $remote){
		$name = $remote->getRemoteName();
		$rssicmd = $eqLogic->getCmd('info', 'rssi' . $name);
		if (is_object($rssicmd)) {
			$rssiantenna = $rssicmd->execCmd();
			$antennaname = $name;
			$signalLevel = 'success';
			if ($rssiantenna <= -150) {
				$signalLevel = 'none';
			}elseif ($rssiantenna <= -92) {
				$signalLevel = 'danger';
			} elseif ($rssiantenna <= -86) {
				$signalLevel = 'warning';
			} elseif ($rssiantenna <= -81) {
				$signalLevel = 'yellow';
			}
			if ($signalLevel!='none' && $signalLevel!='yellow' && $rssiantenna != ''){
				$rssi = $rssi . '<span class="label label-'.$signalLevel.'" style="font-size : 0.9em;cursor:default;padding:0px 5px;">' . $rssiantenna .'dBm (' . ucfirst($antennaname) .')</span></br>';
			} else if ($signalLevel=='yellow' && $rssiantenna != ''){
				$rssi = $rssi . '<span class="label" style="font-size : 0.9em;cursor:default;padding:0px 5px;background-color:#cccc00">' . $rssiantenna .'dBm (' . ucfirst($antennaname) .')</span></br>';
			}
		}	
	}
	$rssicmd = $eqLogic->getCmd('info', 'rssilocal');
	if (is_object($rssicmd)) {
		$rssiantenna = $rssicmd->execCmd();
		$antennaname = 'local';
		$signalLevel = 'success';
			if ($rssiantenna <= -150) {
				$signalLevel = 'none';
			}elseif ($rssiantenna <= -92) {
				$signalLevel = 'danger';
			} elseif ($rssiantenna <= -86) {
				$signalLevel = 'warning';
			} elseif ($rssiantenna <= -81) {
				$signalLevel = 'yellow';
			}
			
		if ($signalLevel!='none' && $signalLevel!='yellow' && $rssiantenna != ''){
			$rssi = $rssi . '<span class="label label-'.$signalLevel.'" style="font-size : 0.9em;cursor:default;padding:0px 5px;">' . $rssiantenna .'dBm (' . ucfirst($antennaname) .')</span>';
		} else if ($signalLevel=='yellow' && $rssiantenna != ''){
			$rssi = $rssi . '<span class="label" style="font-size : 0.9em;cursor:default;padding:0px 5px;background-color:#cccc00">' . $rssiantenna .'dBm (' . ucfirst($antennaname) .')</span>';
		}
	}
	$antenna = $eqLogic->getConfiguration('antenna','local');
	$antennareceive = $eqLogic->getConfiguration('antennareceive','local');
	if ($antenna != 'local' && $antenna != 'all'){
		$remote = blea_remote::byId($antenna);
		if (is_object($remote)){
			$antenna = $remote->getRemoteName();
		} else {
			$remote = 'N/A';
		}
	}
	if ($antenna == 'all'){
		$antenna = 'Tous';
	}
	if ($antennareceive != 'local' && $antennareceive != 'all'){
		$remote = blea_remote::byId($antennareceive);
		if (is_object($remote)){
			$antennareceive = $remote->getRemoteName();
		} else {
			$antennareceive = 'N/A';
		}
	}
	if ($antennareceive == 'all'){
		$antennareceive = 'Tous';
	}
	$present = 0;
	$presentcmd = $eqLogic->getCmd('info', 'present');
	if (is_object($presentcmd)) {
		$present = $presentcmd->execCmd();
	}
	if ($present == 1){
		$present = '<span class="label label-success" style="font-size : 1em;" title="{{Présent}}"><i class="fa fa-check"></i></span>';
	} else {
		$present = '<span class="label label-danger" style="font-size : 1em;" title="{{Absent}}"><i class="fa fa-times"></i></span>';
	}
	$refresh = $eqLogic->getConfiguration('needsrefresh', 0);
	if ($refresh == 1){
		$refresh = '<span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fa fa-check"></i></span>';
	} else {
		$refresh = '<span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fa fa-times"></i></span>';
	}
	echo '<td>' . $rssi . '</td>';
	echo '<td><span class="label label-info" style="font-size : 1em;cursor:default;">' . ucfirst($antenna) . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em;cursor:default;">' . ucfirst($antennareceive) . '</span></td>';
	echo '<td>' . $present . '</td>';
	echo '<td>' . $refresh . '</td>';
	echo '<td><span class="label label-info" style="font-size : 1em;cursor:default;">' . $eqLogic->getStatus('lastCommunication') . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em;cursor:default;">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
}
?>
	</tbody>
</table>
