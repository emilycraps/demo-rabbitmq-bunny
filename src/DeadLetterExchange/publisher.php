<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('failed_mails', 'direct');
$queue = $channel->queueDeclare('failed_mails');
$channel->queueBind($queue->queue, 'failed_mails');

$channel->exchangeDeclare('send_mail', 'direct');
$queue = $channel->queueDeclare('send_mail', false, false, false, false, false, ['x-dead-letter-exchange' => 'failed_mails']);
$channel->queueBind($queue->queue, 'send_mail');

for ($i = 0; $i < 10; $i++) {
    $data = sprintf("Message %d", $i+1);

    $channel->publish($data, [], 'send_mail');
    echo sprintf(' [x] Sent : %s', $data) . PHP_EOL;
}

$channel->close();
$client->disconnect();