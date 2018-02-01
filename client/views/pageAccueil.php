<?php
session_start();
if(isset($_POST['text']))
    {
        $service_url = 'http://127.0.0.1:8000/message';
        $curl = curl_init($service_url);
        $curl_post_data = array(
                'text' => $_POST['text'],
                'creator_id' => $_SESSION['id']
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        $decoded = json_decode($curl_response); 
    }
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
        <link rel="stylesheet" href="../css/style.css">
        <title></title>
    </head>
        <?php
            // après validation du formulaire d'ajou 
        if ((!empty($_POST['email']) || (isset($_SESSION['mail']))) && (!empty($_POST['password']) || (isset($_SESSION['password']))) ) {

            $service_url = 'http://127.0.0.1:8000/connexion';
            $curl = curl_init($service_url);
            $curl_post_data = array(
                'email' => isset($_POST['email']) ? $_POST['email'] : $_SESSION['mail'],
                'password' => isset($_POST['password']) ? $_POST['password'] : $_SESSION['password']
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
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            $decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }
            if ($httpcode == "200")
            {
                unset($_SESSION['mail']);
                unset($_SESSION['id']);
                unset($_SESSION['password']);
                unset($_SESSION['follows']);
                
                $_SESSION['mail'] = $decoded->email;
                $_SESSION['id'] = $decoded->id;
                $_SESSION['password'] = $decoded->password;
                $_SESSION['follows'] = $decoded->follows;
                ?>
                <body>
                    <div class="divGeneral">
                        <div class="divG">
                            <center><h2>Liste des messages</h2></center>
                            <div class="blocMessages">
                            <?php
                            $service_url = 'http://127.0.0.1:8000/message/'.$_SESSION['id'];
                            $curl = curl_init($service_url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $curl_response = curl_exec($curl);
                            if ($curl_response === false) {
                                $info = curl_getinfo($curl);
                                curl_close($curl);
                                die('error occured during curl exec. Additioanl info: ' . var_export($info));
                            }
                            curl_close($curl);
                            $decoded = json_decode($curl_response);
                            
                            foreach ($decoded as $message)
                            {
                                ?><ul><?php
                                    ?>
                                    <li>
                                        Message : <?php echo $message->text.'  Utilisateur  : '.$message->creator->username;?>
                                    </li><?php 
                                ?></ul><?php 
                            }
                            ?>
                            </div>
                            <form action="pageAccueil.php" method="post">
                                <textarea rows="" cols="" name="text"></textarea>
                                <input  type="submit" name="envoyer" value="Envoyer">
                            </form>
                        </div>
                        <div class="divD">
                            <center><h2>Liste des personnes à suivres</h2></center>
                            <?php
                            $service_url = 'http://127.0.0.1:8000/users';
                            $curl = curl_init($service_url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $curl_response = curl_exec($curl);
                            if ($curl_response === false) {
                                $info = curl_getinfo($curl);
                                curl_close($curl);
                                die('error occured during curl exec. Additioanl info: ' . var_export($info));
                            }
                            curl_close($curl);
                            $decoded = json_decode($curl_response);
                            
                            ?><div class="listeSuivis">
                                <table>
                                    <?php
                                        foreach($decoded as $valeur)
                                        {
                                            $found = FALSE;
                                            foreach ($_SESSION['follows'] as $test)
                                            {
                                                if ($test->followed->id == $valeur->id)
                                                {
                                                    $found = true;
                                                }
                                            }
                                            if ($found)
                                            {
                                                ?>
                                                <tr>
                                                    <td class="boutonF">
                                                        <form action="pageAccueil.php" method="post">
                                                            <input  type="hidden" name="idUn" value="<?php echo $valeur->id ?>">
                                                            <input  style="width:50%;" type="submit" name="envoyer" value="UnFollow">
                                                        </form>
                                                    </td>
                                                <?php 
                                            } 
                                            else
                                            {
                                            ?>
                                            <tr>
                                                <td class="boutonF">
                                                    <form action="pageAccueil.php" method="post">
                                                        <input  type="hidden" name="id" value="<?php echo $valeur->id ?>">
                                                        <input  style="width:50%;" type="submit" name="envoyer" value="Follow">
                                                    </form>
                                                </td>
                                            <?php
                                            }?>
                                                <td class="name">
                                                    <?php echo $valeur->username;?>
                                                </td>
                                            </tr>
                                            <?php
                                        } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </body>
                <?php
                if (isset($_SESSION['id']) && (!empty($_POST['id'])))
                {
                    
                    $service_url = 'http://127.0.0.1:8000/follow';
                    $curl = curl_init($service_url);
                    $curl_post_data = array(
                            'follower_id' => $_SESSION['id'],
                            'followed_id' => $_POST['id']
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
                    $curl_response = curl_exec($curl);
                    if ($curl_response === false) {
                        $info = curl_getinfo($curl);
                        curl_close($curl);
                        die('error occured during curl exec. Additioanl info: ' . var_export($info));
                    }
                    curl_close($curl);
                    $decoded = json_decode($curl_response);
                    echo '<script language="JavaScript" type="text/javascript">window.location.replace("pageAccueil.php");</script>';
                }
                if (isset($_SESSION['id']) && (!empty($_POST['idUn'])))
                {
                    $service_url = 'http://127.0.0.1:8000/follow';
                    $curl = curl_init($service_url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                    $curl_post_data = array(
                            'follower_id' => $_SESSION['id'],
                            'followed_id' => $_POST['idUn']
                    );
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
                    $response = curl_exec($curl);
                    if ($curl_response === false) {
                        $info = curl_getinfo($curl);
                        curl_close($curl);
                        die('error occured during curl exec. Additioanl info: ' . var_export($info));
                    }
                    echo '<script language="JavaScript" type="text/javascript">window.location.replace("pageAccueil.php");</script>';
                }
            }
            else
            {
                $_SESSION['badLogin'] = true;
                echo'<script>window.location="../index.php";</script>';
            }
        }
        else
        {   
            $_SESSION['badLogin'] = true;
            echo'<script>window.location="../index.php";</script>';
        }
        ?>
        
</html>

