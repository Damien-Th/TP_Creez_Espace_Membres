<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Connexion</title>
</head>
<body>

<?php 

include("connexionbdd.php");

$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if (isset($_POST["pseudo"]) && $_POST["pseudo"] !="") {
    $pseudo = $_POST['pseudo'];
 
    //  Récupération de l'utilisateur et de son pass hashé
    $req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
    $req->execute(array(
    'pseudo' => $pseudo));
    $resultat = $req->fetch();

    // Comparaison du pass envoyé via le formulaire avec la base
    $isPasswordCorrect = password_verify($_POST['pass'], $resultat['pass']);

    if (!$resultat) {
        $erreur = 'Mauvais identifiant ou mot de passe !';
    } else {
        if ($isPasswordCorrect) {
            session_start();
            $_SESSION['id'] = $resultat['id'];
            $_SESSION['pseudo'] = $pseudo;
            header("location: profil.php?pseudo=".$_SESSION['pseudo']);
            } else {
                $erreur = 'Mauvais identifiant ou mot de passe !';
            }
    }
    if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
    {
    echo 'Bonjour ' . $_SESSION['pseudo'];
    }
    $req->closeCursor();
}
if(!isset($_SESSION['pseudo'])){
    ?>
    <form action="connexion.php" method="post">
    <table>
            <tr>
                <td class="alignright_td">
                    <label for="pseudo">Nom :</label>
                </td>
                <td>
                    <input type="text" name="pseudo">
                </td>
            </tr>
            <tr>
            <td class="alignright_td">
                    <label for="pass">Mot de passe :</label>
                </td>
                <td>
                    <input type="password" name="pass">

                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <br>
            <input class="aligncenter" type="submit" value="Connexion">
                </td>
            </tr>
        </table>
    </form>
    <?php
}
    if(isset($erreur)){
        echo $erreur;
    }
    ?>
</body>
</html>



