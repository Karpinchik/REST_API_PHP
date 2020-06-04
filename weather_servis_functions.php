<?php
$url = 'api.openweathermap.org/data/2.5/weather';

$id_city = 6434291;

$params = [
    'id'=> $id_city,
    'appid' => '8ddfcfdd9b26d3f840a38e0165e8b3de',
    'units' => 'metric',
    'lang' => 'en'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url. '?' . http_build_query($params));

$response = curl_exec($ch);

$data = json_decode($response, 1);

if($data['cod'] == 404)
{
    $result['status'] = 'err';
    $result['massage'] = 'Ошибка в ответе с стороннего сервиса';
    echo json_encode($result);
} else {
    $result = [
        'City_name' => $data['name'],
        'Temperature' => $data['main']['temp'],
        'Wind_Speed' => $data['wind']['speed'],
        'Weather_condition' => $data['clouds']['all'],
        'Weather_icon_id' => $data['weather'][0]['icon'],
        'status' => true
    ];
    echo json_encode($result);
}

curl_close($ch);
