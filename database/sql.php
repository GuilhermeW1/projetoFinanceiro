<?php

function select($table, $columns){
    $con = require 'connection.php';

    $query = $con->query("select {$columns} from $table ");

    return $query->fetchAll();
}


/*
    no insert poderia mandar um array e fazer o $sql separado 
    onde o array e passado por um  'for' e incrementado cada elemetno usando 
    algum metodo parecido com o append() no java  

    algo assim
    id
    append(, nome);
    append(, idade);
*/
function insert($table, $values){
    try{
    $con = require 'connection.php';

    var_dump($values);
    $sql = "insert into $table values( default, {$values} )";
    var_dump($sql);
    $query = $con->query($sql);

    if($query){
        var_dump($sql);
        return true;
    }
}catch(PDOException $e){
    return $e->getMessage();
}
    
    

}