<?php

session_start();

include("connexionbdd.php");

$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_SESSION['pseudo'])) {

    $requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
    $requser->execute(array($_SESSION['pseudo']));
    $user = $requser->fetch();

    if (isset($_POST['newpseudo']) && !empty($_POST['newpseudo']) && $_POST['newpseudo'] != $user['pseudo']) {
        $newSpeudo = htmlspecialchars($_POST['newpseudo']);
        $updateSpeudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
        $updateSpeudo->execute(array($newSpeudo, $user['id']));
        $_SESSION['pseudo'] = $newSpeudo;
        header('location: profil.php?pseudo=' . $_SESSION['pseudo']);
    }

    if (isset($_POST['newemail']) && !empty($_POST['newemail']) && $_POST['newemail'] != $user['email']) {
        $newemail = htmlspecialchars($_POST['newemail']);
        $updatemail = $bdd->prepare("UPDATE membres SET email = ? WHERE id = ?");
        $updatemail->execute(array($newemail, $user['id']));
        header('location: profil.php?pseudo=' . $_SESSION['pseudo']);
    }

    if (isset($_POST['newpass']) && !empty($_POST['newpass'])) {
        if ($_POST['newpass'] === $_POST['confirmnewpass']) {
            $newpass =  $pass_hache = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
            $updatepass = $bdd->prepare("UPDATE membres SET pass = ? WHERE id = ?");
            $updatepass->execute(array($newpass, $user['id']));
            header('location: profil.php?pseudo=' . $_SESSION['pseudo']);
        } else {
            $erreur  = "les mots de passe ne corresponde pas";
        }
    }

    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {

        $tailleMax = 2097152;
        $extensionValides = array('jpg', 'jpeg', 'gif', 'png');
        if ($_FILES['avatar']['size'] <= $tailleMax) {

            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
            if (in_array($extensionUpload, $extensionValides)) {

                $chemin = "membres/avatars/" . $_SESSION['pseudo'] . "." . $extensionUpload;
                $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if ($resultat) {

                    $updateAvatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
                    $updateAvatar->execute(array(
                        'avatar' => $_SESSION['pseudo'] . "." . $extensionUpload,
                        'id' => $user['id']
                    ));

                    header('location: profil.php?pseudo=' . $_SESSION['pseudo']);
                } else {

                    $erreur =  " Erreur durant l'importation de votre photo de profil";
                }
            } else {
                $erreur = " votre photo de profil n'est pas au bon format";
            }
        } else {
            $erreur = " votre photo de profil ne doit pas dépasser 2Mo";
        }
    }

?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Editer mon Profil</title>
    </head>

    <body>
        <h2 class="aligncenter">Editer mon Profil</h2>
        <form method="POST" action="editionProfil.php" enctype="multipart/form-data">
            <p> <label for="newpseudo">Nom </label><input type="text" name="newpseudo" value=<?php echo $user['pseudo'] ?>></p>
            <p> <label for="newemail">EMAIL </label><input type="text" name="newemail" value=<?php echo $user['email'] ?>></p>
            <p> <label for="newpass">New Pass </label><input type="password" name="newpass" placeholder="new pass"></p>
            <p> <label for="newpass">Confirm New Pass </label><input type="password" name="confirmnewpass" placeholder="confirm new pass"></p>
            <p><label for="avatar">Avatar </label><input type="file" name="avatar" /></p>
            <input class="aligncenter" type="submit" value="mettre à jour profil">
        </form>

        <?php
        if (isset($erreur)) {
            echo $erreur;
        }
        ?>
        <p class="aligncenter"><a href="profil.php?pseudo=<?php echo $_SESSION['pseudo'] ?>">retour</a></p>
    </body>

    </html>
<?php
} else {
    header("location: connexion.php");
}

?>