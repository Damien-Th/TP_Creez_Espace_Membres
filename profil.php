<?php

session_start();

include("connexionbdd.php");

$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

//verifier que le parametre de l'URl corespond au pseudo qu'on a enregistrer
$pseudo = $_GET['pseudo'];
$reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
$reqpseudo->execute(array($pseudo));
$pseudoexist = $reqpseudo->rowCount();
$userinfo = $reqpseudo->fetch();

if(isset($_GET['pseudo']) && $pseudoexist == 1){
    if(isset($_SESSION['pseudo']) && $userinfo['pseudo'] == $_SESSION['pseudo']){

  
echo "vous êtes connecter " . $pseudo;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Profil</title>
</head>
<body>
<?php
if(!empty($userinfo['avatar'])){
?>
<p><img src="membres/avatars/<?php echo $userinfo['avatar'];?>" width="150"/></p>
<?php
}
?>
<p> Mon Pseudo : <?php echo $userinfo['pseudo']; ?></p>
<p> Mon Mail: <?php echo $userinfo['email']; ?> </p>
    <p><a href="editionProfil.php">Editer mon profil</a></p>
    <a href="deconnexion.php">Déconnexion</a><br />
</body>
</html>
<?php
  }
}
?>


