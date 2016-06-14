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

if(!defined('INSIDE')){ die("attemp hacking");}

class debug
{
	var $log,$numqueries;

	function debug()
	{
		$this->vars = $this->log = '';
		$this->numqueries = 0;
	}

	function add($mes)
	{
		$this->log .= $mes;
		$this->numqueries++;
	}

	function echo_log()
	{
		echo  "<br><table><tr><td class=k colspan=4><a href=".ROOT_PATH."admin/settings.php>Debug Log</a>:</td></tr>".$this->log."</table>";
		die();
	}

	function error($message,$title)
	{
		global $link,$game_config;
		if($game_config['debug']==1){
			echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
			echo  "<table>".$this->log."</table>";
		}

			global $user;
			include(ROOT_PATH . 'config.'.PHPEXT);
			if(!$link) die('La base de donnee n est pas disponible pour le moment, desole pour la gene occasionnee...');
			$query = "INSERT INTO {{table}} SET
				`error_sender` = '{$user['id']}' ,
				`error_time` = '".time()."' ,
				`error_type` = '{$title}' ,
				`error_text` = '".mysql_escape_string($message)."';";
			$sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"].'errors',$query))
				or die('error fatal');
			$query = "explain select * from {{table}}";
			$q = mysql_fetch_array(mysql_query(str_replace("{{table}}", $dbsettings["prefix"].
				'errors', $query))) or die('error fatal: ');


			if (!function_exists('message'))
				echo "Erreur, merci de contacter l'admin. Erreur n�: <b>".$q['rows']."</b>";
			else
				message("Erreur, merci de contacter l'admin. Erreur n�: <b>".$q['rows']."</b>", "Erreur");
		//}

		die();
	}


}

?>
