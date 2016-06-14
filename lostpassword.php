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

define('INSIDE' , true);
define('INSTALL' , false);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) .'/common.php';

define('ADMINEMAIL',"no-reply@xnova-legacies.org"); //Changez l'email duquel on va envoyer le message
define('GAMEURL',"http://".$_SERVER['HTTP_HOST']."/");

includeLang('lostpassword');

    if($_GET['action'] == '1'){

       $add = 0;
       $name = doquery("SELECT * FROM {{table}} WHERE `username`='{$_POST['pseudo']}'",'users',true);
       $email = doquery("SELECT * FROM {{table}} WHERE `email_2`='{$_POST['email']}'",'users',true);
       if(!$name){$add++; message('Ce nom de joueur n a pas ete trouve','Erreur','lostpassword.php');}
       if(!$email){$add++; message('Cette adresse email n a pas ete trouvee!','Erreur','lostpassword.php');}
       if(!$_POST['pseudo']){$add++; message('Entrez votre pseudo!','Erreur','lostpassword.php');}
       if(!$_POST['email']){$add++; message('Entrez un email!','Erreur','lostpassword.php');}
       if($name['id']!=$email['id']){$add++; message('L adresse mail ne correspond pas au pseudo!!','Erreur','lostpassword.php');}


    if($add==0){
    $user_array = doquery("SELECT `email`,`username` FROM {{table}} WHERE `email` = '{$_POST['email']}' AND `username` = '{$_POST['pseudo']}' LIMIT 1","users",true);
    $email = $_POST['email'];
    $email = $_POST['email'];
    $hashh = (time());
    $actor = "From: Serveur Xnova";  // Changez le nom du serv ici
    $up = "Serveur Xnova - Changer le mot de passe"; // Ici aussi
    mail($email, $up, "
    Vous devez changer votre mot de passe dans votre compte mais pour vous logger vous pouvez utiliser le
    mot de passe :

    Votre nouveau mot de passe est : $hashh

    Attention, n oubliez pas de changer votre mot de passe apres s etre connect� !

      ", $actor);

    $user_array = doquery("SELECT `email_2` FROM {{table}} WHERE `email_2` = '{$_POST['email']}' LIMIT 1","users",true);
    $md5newpass = md5($hashh);

    if($user_array)
    {
    doquery("UPDATE {{table}} SET `password`='{$md5newpass}' WHERE `email_2`='{$_POST['email']}'",'users');
    message('Mot de passe envoye ! Veuiller regarder dans votre boite mail, ou dans vos spam!','Nouveau mot de passe','index.php');

    }
    else
    message('Cette email n existe pas !','Erreur');
    }}


       $parse = $lang;
       $page = parsetemplate(gettemplate('lostpassword'), $parse);

       display($page,$lang['registry']);
