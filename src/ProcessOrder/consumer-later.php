<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('process_order', 'direct');
$queue = $channel->queueDeclare('process_order_later');

$channel->queueBind($queue->queue, 'process_order', 'delivery-later');


echo ' [*] Waiting for orders, delivery date AFTER tomorrow. To exit press CTRL+C', "\n";

$channel->run(
    function (\Bunny\Message $message, \Bunny\Channel $channel, \Bunny\Client $client) {
        echo sprintf(' [x] %s : %s', $message->routingKey, $message->content) . PHP_EOL;
    },
    $queue->queue,
    '',
    false,
    true
);