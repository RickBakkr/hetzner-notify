<?php
require 'vendor/autoload.php';
require 'config.php';

$cache = "";

$curl = curl_init();

curl_setopt_array(
    $curl,
    [
        CURLOPT_URL => "https://www.hetzner.com/a_hz_serverboerse/live_data.json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Cache-Control: no-cache",
            "X-Kind-Regards-From: Rick B. - Hetzner <3",
        ],
    ]
);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($caching && file_exists('cache.txt')) {
    $contents = file_get_contents('cache.txt');
    $items = explode(PHP_EOL, $contents);
}

if ($err) {
    echo "cURL Error #:".$err;
} else {
    $data = json_decode($response);
    $data = $data->server;
    $sortedList = [];
    foreach ($data as $keyId => $server) {
        $cache .= $server->key.PHP_EOL;
        if ($server->ram >= $minRam && $server->price <= $maxPrice) {
            if (!$caching or !in_array($server->key, $items)) {
                $sortedList[$keyId] = $server->cpu_benchmark;
            }
        }
    }
}

$msgArray = [];

arsort($sortedList);
$sortedList = array_slice($sortedList, 0, $maxList, true);
$i = 0;

foreach ($sortedList as $keyId => $bench) {
    $server = $data[$keyId];

    if ($i == 0) {
        $message = '';
        $message .= $mention;
        $i = 1;
    }

    $msgArray[] = (new \HetznerNotify\ServerMessage($server))->asString();
}

if (count($msgArray) < 1) {
    // Exit the script, do not fire..
    exit();
} else {
    // We have some message to share, POST it to RC.
    if ($client == 'rocketchat') {
        $message = implode('', $msgArray);

        if (isset($thanks) && $thanks !== false) {
            $message .= PHP_EOL.PHP_EOL;
            $message .= '-------------------------------------'.PHP_EOL;
            $message .= 'Hetzner Serverboerse notifier bot has been written by Rick Bakker'.PHP_EOL;
            $message .= '-------------------------------------'.PHP_EOL;
        }

        $client = new \RocketChatPhp\Client($host, $token);
        $client->payload(
            [
                'text' => $message,
            ]
        );
    } elseif ($client == 'discord') {
        $webhook = new \DiscordWebhooks\Client($discord_webhook_url);
        $embed = new \DiscordWebhooks\Embed();
        $embed->description($message);
        $message .= 'I have found deals you might find interesting!';
        $queue = $webhook->username('Hetzner Notifier Bot')->message($message);

        foreach ($msgArray as $message) {
            $embed = new \DiscordWebhooks\Embed();
            $embed->description($message);
            $queue = $queue->embed($embed);
        }

        $queue->send();
    } elseif ($client == 'raw') {
        $message = implode('', $msgArray);

        if (isset($thanks) && $thanks !== false) {
            $message .= PHP_EOL.PHP_EOL;
            $message .= '-------------------------------------'.PHP_EOL;
            $message .= 'Hetzner Serverboerse notifier bot has been written by Rick Bakker'.PHP_EOL;
            $message .= '-------------------------------------'.PHP_EOL;
        }

        echo $message;
    }
}

if ($caching) {
    file_put_contents('cache.txt', $cache);
}
