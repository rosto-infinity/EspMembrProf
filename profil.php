<?php
session_start();
 
require'db.php';
 
if(isset($_GET['id']) AND $_GET['id'] > 0) {
   $getid = intval($_GET['id']);
   $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
   $requser->execute(array($getid));
   $userinfo = $requser->fetch();
?>
<html>
   <head>
      <title>TUTO PHP</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Profil de <?= $userinfo['pseudo']; ?></h2>
         <br /><br />
                        <?php if(!empty($userinfo['avatar'])){?>

                           <img src="membres/avatars/<?= $userinfo['avatar']; ?>" width="222">
                        <?php 
                        }?>

         <br /><br />


         Pseudo = <?= $userinfo['pseudo']; ?>
         <br />
         Mail = <?= $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
         ?>
         <br />
         <a href="editionprofil.php">Editer mon profil</a>
         <a href="deconnexion.php">Se déconnecter</a>
         <?php
         }
         ?>
      </div>
   </body>
</html>
<?php   
}
?>