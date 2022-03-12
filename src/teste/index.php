<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php 
    /*
    $select = require  '../../database/sql.php';
    $select = select('moviements','idUser, idMoviement');
    var_dump($select);
    */

    $insert = require  '../../database/sql.php';
    $nome = 'eu';
    $insert = insert("teste",  " '$nome' , 18 ");

    if($insert){
        echo  'deu certo';
    }else{
        echo  'deu errrado';
        var_dump($insert);
    }
?>
</body>
</html>