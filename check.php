<?php
require 'vendor/autoload.php';
require 'config.php';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.hetzner.com/a_hz_serverboerse/live_data.json",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Cache-Control: no-cache",
    "X-Kind-Regards-From: Rick B. - Hetzner <3"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

$vatPRC = (100+$vat) / 100;

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $data = json_decode($response);
  $data = $data->server;
  $sortedList = [];
  foreach($data as $keyId => $server) {
    if($server->ram >= $minRam && $server->price <= $maxPrice) {
      $sortedList[$keyId] = $server->cpu_benchmark;
    }
  }
}

arsort($sortedList);
$sortedList = array_slice($sortedList, 0, $maxList, true);
$i = 0;
foreach($sortedList as $keyId => $bench) {
  $server = $data[$keyId];
  if($i == 0) {
    $message = '';
    $message .= $mention . PHP_EOL . PHP_EOL;
    $i = 1;
  }
  $flag = ':de:';
  if ($server->datacenter[1] == 'HEL') {
    $flag = ':fi:';
  }
  $message .= '** HETZNER DEAL FOUND **' . PHP_EOL;
  $message .= $server->freetext . PHP_EOL;
  $message .= 'CPU Benchmark: ' . $server->cpu_benchmark . PHP_EOL;
  $message .= PHP_EOL;
  $message .= 'Dedicated is located in: ' . array_shift($server->datacenter) . ' ' . $flag . PHP_EOL;
  $message .= PHP_EOL;
  $message .= 'Cost is: €' . $server->price . ' (approx. €'  . $server->price*$vatPRC  . ' incl. ' . $vat . '% MwSt) ' . PHP_EOL;
  $message .= PHP_EOL;
  $message .= 'https://robot.your-server.de/order/marketConfirm/' . $server->key . PHP_EOL;
  $message .= PHP_EOL . PHP_EOL;
}

if(!isset($message)) {
  // Exit the script, do not fire the POST..
  exit();
} else {
  if(isset($thanks) && $thanks !== false) {
    $message .= PHP_EOL . PHP_EOL;
    $message .= '-------------------------------------' . PHP_EOL;
    $message .= 'Hetzner Serverboerse notifier bot for Rocket Chat has been written by Rick Bakker' . PHP_EOL;
    $message .= '-------------------------------------' . PHP_EOL;
  }
  // We have some message to share, POST it to RC.
  $client = new \RocketChatPhp\Client($host, $token);
  $client->payload([
      'text' => $message
  ]);
}
