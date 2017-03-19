<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('send_confirmation_mail', 'direct');
$queue = $channel->queueDeclare('send_confirmation_mail');

$channel->queueBind($queue->queue, 'send_confirmation_mail');


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$channel->run(
    function (\Bunny\Message $message, \Bunny\Channel $channel, \Bunny\Client $client) {
        echo sprintf(' [x] : %s', $message->content) . PHP_EOL;
    },
    $queue->queue,
    '',
    false,
    true
);