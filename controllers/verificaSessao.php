<?php

class verificaSessao{

private function verificar(){
    session_start();
if(!isset($_SESSION['user'])){
    header('Location: ../../src/login/index.php');
    return false;
}else{
    return true;
}

}


function check(){
    
    $a = $this->verificar();
    

}


}

$a = new verificaSessao;
$a->check();

