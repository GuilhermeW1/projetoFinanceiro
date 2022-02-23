<?php

//nada funciona ;-;
//class AtivoController{

//private function retornaArray(){
    try{


    session_start();
    $conexao = require '../database/connection.php';
    
    $sql = "select id_ativo,cd_ativo from ativo order by cd_ativo";
    $stmt =  $conexao->prepare($sql);
    $stmt->execute();

    $array;
    $contador = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC) ){
        $array[$contador] = $row;
        $contador++;
    }

    
    return $array;
}catch(PDOException $e){
    echo 'erro ' .$e->getMessage();
}
/*
}
function a(){
    $b = $this->retornaArray();
    //var_dump($b);
    return $b; 
}
}
*/


?>

