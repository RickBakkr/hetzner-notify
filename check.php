<?php
require 'vendor/autoload.php';

use \HetznerNotify\ServerMessage as ServerMessage;
use \HetznerNotify\Config as Config;

// load configuration
$config = new Config(true);

$cache = "";

// get source from hetzner
$guzzleClient = new \GuzzleHttp\Client();
$res = $guzzleClient->request('GET', 'https://www.hetzner.com/a_hz_serverboerse/live_data.json');
if ($res->getStatusCode() != '200') {
    echo "can't load hetzner data! Error ".$res->getStatusCode().die();
}
$data = json_decode($res->getBody());
$data = $data->server;

$items = [];
if($config->get('cache') && file_exists('cache.txt')) {
  $contents = file_get_contents('cache.txt');
  $items = explode(PHP_EOL, $contents);
}

$filteredServers = (new \HetznerNotify\ServerFilterService($data, $config->get('filter')))
    ->process()
    ->getServers();

$sortedList = [];
foreach($filteredServers as $keyId => $server) {
    $cache .= $server->key . PHP_EOL;
    if (!$config->get('cache') or !in_array($server->key, $items)) {
      $sortedList[$keyId] = $server->cpu_benchmark;
    }
}

$msgArray = [];

arsort($sortedList);
$sortedList = array_slice($sortedList, 0, $config->get('max_list'), true);
$i = 0;
foreach($sortedList as $keyId => $bench) {
  $server = $filteredServers[$keyId];
  if($i == 0) {
    $message = '';
    $message .= $config->get('mention');
    $i = 1;
  }

  $msgArray[] = (new ServerMessage($server, $config->get('vat')))->asString();
}

$notifyClient = $config->get('client');
if(count($msgArray) < 1) {
  // Exit the script, do not fire..
  exit();
} else {
  // We have some message to share, POST it to RC.
  if($notifyClient == 'rocketchat') {
    $message = '';
    foreach($msgArray as $ms) {
      $message .= $ms;
    }
    if($config->get('thanks') !== false) {
      $message .= PHP_EOL . PHP_EOL;
      $message .= '-------------------------------------' . PHP_EOL;
      $message .= 'Hetzner Serverboerse notifier bot has been written by Rick Bakker' . PHP_EOL;
      $message .= '-------------------------------------' . PHP_EOL;
    }

    $client = new \RocketChatPhp\Client($config->get('host'), $config->get('token'));
    $client->payload([
        'text' => $message
    ]);
  } elseif($notifyClient == 'discord') {
    $webhook = new \DiscordWebhooks\Client($config->get('discord_webhook_url'));
    $embed = new \DiscordWebhooks\Embed();
    $embed->description($message);
    $message .= 'I have found deals you might find interesting!';
    $queue = $webhook->username('Hetzner Notifier Bot')->message($message);
    foreach($msgArray as $message) {
      $embed = new \DiscordWebhooks\Embed();
      $embed->description($message);
      $queue = $queue->embed($embed);
    }
    $queue->send();
  } elseif($notifyClient == 'raw') {
    $message = '';
    foreach($msgArray as $ms) {
      $message .= $ms;
    }
    if($config->get('thanks') !== false) {
      $message .= PHP_EOL . PHP_EOL;
      $message .= '-------------------------------------' . PHP_EOL;
      $message .= 'Hetzner Serverboerse notifier bot has been written by Rick Bakker' . PHP_EOL;
      $message .= '-------------------------------------' . PHP_EOL;
    }
    echo $message;
  }
}

if($config->get('cache')) {
  file_put_contents('cache.txt', $cache);
}
