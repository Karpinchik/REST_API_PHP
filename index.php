<?php
require 'db_data.php';
require 'functions.php';


$get =  $_GET['q'];
$parametrs = explode('/', $get);
$method = $_SERVER['REQUEST_METHOD'];

$key_1 = $parametrs[0];
$key_2 = $parametrs[1];

if($method == 'GET')
{
    if(!empty($key_2)){
        getPhoneByName($key_2);
    } else if ($get === 'phones') {
        getAllPhones();
    }
}

if($method === 'POST' )
{
    if($key_1 === 'phones') {
        setNumber();
    }
}

if($method === 'PATCH' )
{
    if($key_1 === 'phones') {
        if (isset($key_2)) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            updateInfo($data, $key_2);
        }
    }
}


if($method === 'DELETE' )
{
    if($key_1 === 'phones')
    {
        if(isset($key_2)){
            deleteItem($key_2);
        }
    }

}

?>





