<?php 
if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
    echo 'Bonjour ' . $_SESSION['pseudo'];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Créez un espace membres</title>
</head>

<body>

    <?php 

include("connexionbdd.php");

// Afficher les erreurs
$bdd->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if (isset($_POST["email"]) && $_POST["email"] !="") {
    $passwordlength = strlen($_POST['pass']);
    // Verifie si le password fait plus de 6 caractères
    if($passwordlength >= 6){
        if($_POST["pass"] === $_POST['verifPass']){
            $email = $_POST['email'];
            $reqmail = $bdd->prepare("SELECT * FROM membres WHERE email = ?");
            $reqmail->execute(array($email));
            $mailexist = $reqmail->rowCount();
            $pseudo = $_POST['pseudo'];
            $reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
            $reqpseudo->execute(array($pseudo));
            $pseudoexist = $reqpseudo->rowCount();
             // Verifie si l'adresse mail ou le speudo est déja utilisée
            if($mailexist == 0 && $pseudoexist == 0){
                $pseudo = $_POST['pseudo'];
                // Hachage du mot de passe
                $pass_hache = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                $verifyPass_hache = password_hash($_POST['verifPass'], PASSWORD_DEFAULT);
                $email = $_POST['email'];
                $avatar = "default.jpg";
            
                $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription, avatar) VALUES(:pseudo, :pass, :email, CURDATE(), :avatar)');
                $req->execute(array(
                'pseudo' => $pseudo,
                'pass' => $pass_hache,
                'email' => $email,
                'avatar' => $avatar));
                
                $erreur = ' Inscription complete';
            
                $req->closeCursor();
                }else {
                    $erreur = ' Adresse mail ou speudo déja utilisée';
                }
            }else {
                $erreur = ' le mot de passe ne correspond pas';
            }
    }else{
        $erreur = ' minimum 6 caractères';
    }
}

?>
    <p class="alignright"><a  href="connexion.php">Connexion</a></p>

    <div class="aligncenter">

    <h2>Formulaire d'inscription</h2>
    <!--------------formulaire-------------->
    <form action="index.php" method="post">
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
            <td class="alignright_td">
                    <label for="verifPass">Confirmation du Mot de passe :</label>
                </td>
                <td>
                    <input type="password" name="verifPass">

                </td>
            </tr>
            <tr>
            <td class="alignright_td">
                    <label for="email">Adresse Mail :</label>
                </td>
                <td>
                    <input type="email" name="email">

                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <br>
            <input class="aligncenter" type="submit" value="Enregistrer">
                </td>
            </tr>
        </table>
    </form>
<?php
    if(isset($erreur)){
        echo $erreur;
    }
    ?>
    </div>

</body>

</html>