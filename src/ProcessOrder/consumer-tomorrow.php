<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('process_order', 'direct');
$queue = $channel->queueDeclare('process_order_tomorrow');

$channel->queueBind($queue->queue, 'process_order', 'delivery-tomorrow');


echo ' [*] Waiting for orders, delivery date tomorrow. To exit press CTRL+C', "\n";

$channel->run(
    function (\Bunny\Message $message, \Bunny\Channel $channel, \Bunny\Client $client) {
        echo sprintf(' [x] %s : %s', $message->routingKey, $message->content) . PHP_EOL;
    },
    $queue->queue,
    '',
    false,
    true
);