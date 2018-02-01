<?php
session_start();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title></title>
    </head>
    <body>
        <div class="formulaireInsc">
            <form action="views/pageAccueil.php" method="post" >
                <?php 
                if (isset($_SESSION['badLogin'])) {
                    echo "<p class='badLogin'>Mauvais email ou password</p>";
                    unset($_SESSION['badLogin']);
                }
                ?>
                <p>Email : <input type="mail" name="email"></p>
                <p>Password    : <input type="password" name="password"></p>
                <input type="submit" value="Connexion"><p class="lienCreation"><a href="views/formulaireUser.php">création d'un utilisateur</a></p>
            </form>
        </div>
        <?php
        
        
        // après validation du formulaire d'ajou 
        if ((isset($_POST['username'])) && (isset($_POST['mail'])) && (isset($_POST['password']))) {

            $service_url = 'http://127.0.0.1:8000/user';
            $curl = curl_init($service_url);
            $curl_post_data = array(
                'username' => $_POST['username'],
                'email' => $_POST['mail'],
                'password' => $_POST['password']
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
            $curl_response = curl_exec($curl);
            if ($curl_response === false) {
                echo $curl_response;
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($curl);
            /*$decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }
            echo 'response ok!';
            echo $decoded;
            var_export($decoded->response);*/
        }
        // put your code here
        ?>
    </body>
</html>
