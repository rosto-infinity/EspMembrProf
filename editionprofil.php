<?php
session_start();
 
require'db.php';
 
if(isset($_SESSION['id'])) {
   $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
   $requser->execute(array($_SESSION['id']));
   $user = $requser->fetch();
   if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo']) {

      $newpseudo = htmlspecialchars($_POST['newpseudo']);
      $pseudolength = strlen($newpseudo);
      if($pseudolength <= 255) {

         $reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
         $reqpseudo->execute(array($newpseudo));
         $pseudoexist = $reqpseudo->rowCount();
         if($pseudoexist == 0) {
         $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
         $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
            header('Location: profil.php?id='.$_SESSION['id']);
         }else{
            $erreur = "Pseudo déjà utilisée !";
         }
   
      }else {
         $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
      }
   }
   if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail']) {
      $newmail = htmlspecialchars($_POST['newmail']);

            
                        if(filter_var($newmail, FILTER_VALIDATE_EMAIL)) {
                        
                           $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                           $reqmail->execute(array($newmail));
                           $mailexist = $reqmail->rowCount();
                           if($mailexist == 0) {
      $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
      $insertmail->execute(array($newmail, $_SESSION['id']));
      header('Location: profil.php?id='.$_SESSION['id']);
                           } else {
                              $erreur = "Adresse mail déjà utilisée !";
                           }


                           } else {
                              $erreur = "Votre adresse mail n'est pas valide !";
                           }

   }
   if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2'])) {
      $mdp1 = sha1($_POST['newmdp1']);
      $mdp2 = sha1($_POST['newmdp2']);
      if($mdp1 == $mdp2) {
         $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE id = ?");
         $insertmdp->execute(array($mdp1, $_SESSION['id']));
         header('Location: profil.php?id='.$_SESSION['id']);
      } else {
         $msg = "Vos deux mdp ne correspondent pas !";
      }
   }

                  if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
                     $tailleMax = 2097152;
                     $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
                     if($_FILES['avatar']['size'] <= $tailleMax) {
                        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));

                        if(in_array($extensionUpload, $extensionsValides)) {
                           //si une personne a une id= 22, on aura le mon de son avatar  22.jpg
                           $chemin = "membres/avatars/".$_SESSION['id'].".".$extensionUpload;
                           $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);

                           if($resultat) {
                              $updateavatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                              $updateavatar->execute(array(
                                 'avatar' => $_SESSION['id'].".".$extensionUpload,
                                 'id' => $_SESSION['id']
                                 ));
                              header('Location: profil.php?id='.$_SESSION['id']);
                           } else {
                              $msg = "Erreur durant l'importation de votre photo de profil";
                           }


                        } else {
                           $msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
                        }

                        
                     } else {
                        $msg = "Votre photo de profil ne doit pas dépasser 2Mo";
                     }
                  }



?>
<html>
   <head>
      <title>TUTO PHP</title>
      <meta charset="utf-8">
   </head>
   <body>
   
      <div align="center">
         <h2>Edition de mon profil</h2>
         <div align="left">
            <form method="POST" action="" enctype="multipart/form-data">
               <label>Pseudo :</label>
               <input type="text" name="newpseudo" placeholder="Pseudo" value="<?= $user['pseudo']; ?>" /><br /><br />
               <label>Mail :</label>
               <input type="text" name="newmail" placeholder="Mail" value="<?= $user['mail']; ?>" /><br /><br />
               <label>Mot de passe :</label>
               <input type="password" name="newmdp1" placeholder="Mot de passe"/><br /><br />
               <label>Confirmation - mot de passe :</label>
               <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" /><br /><br />
               <label for="">Avatar :</label>
               <input type="file" name="avatar" /><br /><br />
               <input type="submit" value="Mettre à jour mon profil !" />
            </form>
            <?php if(isset($msg)) { echo $msg; } ?>
         </div>
      </div>
   </body>
</html>

<?php   
}
else {
   header("Location: connexion.php");
}
?>
<?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>


