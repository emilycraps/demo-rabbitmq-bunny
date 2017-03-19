<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('send_confirmation_mail', 'direct');

for ($i = 0; $i < 10; $i++) {
    $data = sprintf("Message %d", $i+1);

    $channel->publish($data, [], 'send_confirmation_mail');
    echo sprintf(' [x] Sent : %s', $data) . PHP_EOL;
}

$channel->close();
$client->disconnect();