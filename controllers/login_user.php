<?php
    session_start();
    include ('../config/conexion.php');
    $userPass = $_POST['inputPassUser'];
    
    $sqlGetUser="SELECT * FROM $tUser WHERE password='$userPass' ";
    $resGetUser=$con->query($sqlGetUser);
    if($resGetUser->num_rows > 0){
        $rowGetUser=$resGetUser->fetch_assoc();
       
        $_SESSION['sessU'] = true;
	$_SESSION['userId'] = $rowGetUser['id'];
	$_SESSION['userName'] = $rowGetUser['ap']." ".$rowGetUser['am']." ".$rowGetUser['nombre'];
        $_SESSION['perfil'] = $rowGetUser['perfil_id'];
        
        echo "true";
        
    }
    else{
        $_SESSION['sessU']=false;
        echo "Error en la consulta<br>".$con->error;
    }
      
?>