<?php
header('Content-type: application/json');
require 'db_data.php';
$pdo = new PDO($dsn, $user, $password, $opt);


/*
 * получение всего списка телефонных номеров при первом параметре /phones
 */
function getAllPhones()    // получаю последний id в бд
{
    global $pdo;
    $data=[];
    $stmt = $pdo->query("SELECT * FROM `persons` LEFT JOIN `allphones` ON persons.id = allphones.id_person");
    if($stmt->rowCount() < 1)
    {

        $res = [
            'status'=> false,
            'message'=> 'not found items'
        ];
        echo json_encode($res);
    } else {
        while($result = $stmt->fetch()) $data[] = $result;
//        http_response_code(204);
        echo json_encode($data);

    }
}


/*
 * получение определенного номера по фамилии используя второй параметр /phones/lastname
 */
function getPhoneByName($key_2)
{
    global $pdo;
    $data=[];
    $stmt = $pdo->query("SELECT * FROM `persons` LEFT JOIN `allphones` ON persons.id = allphones.id_person WHERE `persons`.`name` = '$key_2'");
    while ($result = $stmt->fetch()) $data[] = $result;

    if(!empty($data))
    {
        echo json_encode($data);
    } else {
        $res = [
            'status'=> false,
            'message'=> 'not found items'
        ];
        echo json_encode($res);
    }
}


/*
 * добавление новой записи в телефонную книгу
 */
function setNumber()
{
    global $pdo;

    if(!empty($_POST['name']) & !empty($_POST['phone']))
    {
        $sql = "INSERT INTO `persons`(name, phone) VALUES (:input_name, :input_number)";
        $stmt = $pdo->prepare($sql);
        $params = [':input_name' => $_POST['name'], ':input_number' => intval($_POST['phone'])];
        $stmt->execute($params);

//        http_response_code(201);
        $id = $pdo->lastInsertId();
        if(!empty($_POST['phone_home']) & !empty($_POST['phone_work']))
        {
            $phone_home = $_POST['phone_home'];
            $phone_work = $_POST['phone_work'];
            $stmt1 = $pdo->query("INSERT INTO `allphones`(`phone_home`, `phone_work`, `id_person`) VALUES ($phone_home, $phone_work, $id)");
        }

        $res = [
            'status'=> true,
            'message'=> 'create new item',
            'id'=> $id
        ];
        echo json_encode($res);

    } else {
        $res = [
            'status'=> false,
            'message'=> 'not create new item'
        ];
        echo json_encode($res);
    }
}


/*
 * изменение данных
 */
function updateInfo($data, $key_2)
{
    $name = $data['name'];
    $phone = $data['phone'];
    $phone_home_update = $data['phone_home'];
    $phone_work_update = $data['phone_work'];
    global $pdo;

    $sql = "UPDATE `persons` SET `name`= '$name', `phone`='$phone' WHERE `persons`.`id`='$key_2'";
    $stmt = $pdo->query($sql);

    $stmt1 = $pdo->query("UPDATE `allphones` SET `phone_home` = '$phone_home_update', `phone_work` = '$phone_work_update' WHERE `allphones`.`id_person` ='$key_2' ");

    $res = [
        'status'=> true,
        'message'=> 'item is update'
    ];
    echo json_encode($res);
}

/*
 * удаление данных
 */
function deleteItem($key_2)
{
    global $pdo;
    $sql = "DELETE FROM `persons` WHERE `persons`.`id` = '$key_2'";
//    $sql = "DELETE FROM `allphones` LEFT JOIN `persons` ON `allphones`.`id_person`=`persons`.`id` WHERE `allphones`.`id_person` = '$key_2'";
    $stmt = $pdo->query($sql);

    $res = [
        'status'=> true,
        'message'=> 'item is deletad'
    ];
    echo json_encode($res);

}









