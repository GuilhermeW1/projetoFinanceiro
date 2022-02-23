<?php

class LoginController{


    //esta funcao ira efetivamente fazer o login
private function logar(){
session_start();

if(isset($_POST['user']) && isset($_POST['password']) ){
    $user = $_POST['user'];
    $password = $_POST['password'];

    $con = require '../database/connection.php';

    $sql ="select id_user, user, senha from users where user = '$user' and senha = '$password' ";

    $result =  $con->query($sql);

    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(isset($row['user'])){
        $_SESSION['user'] = $row['user'];
        $_SESSION['id_user'] = $row['id_user'];
        return true;

        //header ('Location: ../src/home/index.php');
    } else{
        //$_SESSION['erro'] = 'Usuario ou senha incorreto';
        //header ('Location: ../src/login/index.php');
        return false;
        

    }



}else{
    /*
    echo 'Usuario ou senha não estao setados';
    header ('Location: ../src/login/index.php');
    */

}


}

//esta e a funcao publica chamada para fazer o login
public function a(){
    $var = $this->logar();
    if($var){
        header ('Location: ../src/home/index.php');
        //return true;
    }else{
        $_SESSION['erro'] = 'Usuario ou senha incorreto';
        header ('Location: ../src/login/index.php');
        //return false;
    }

}

}

$logar = new LoginController;
$logar->a();

/*
if($bol){
    session_start();
    //$_SESSION['id_user'] = $row['id_user'];
    //$_SESSION['user'] = $row['user'];
    header ('Location: ../src/home/index.php');

}else{
    $_SESSION['erro'] = 'Usuario ou senha incorreto';
    header ('Location: ../src/login/index.php');
}
*/

?>