<?php

class Logout {

private function destroy(){
session_start();
session_destroy();

header('Location: ../../src/login/index.php');
}

function logout(){
    $this->destroy();
}

}
//aqui foi de uma maniera melhor acho que estava esquecendo como se prograava dfkkkk

$logout = new Logout;
$logout->logout();