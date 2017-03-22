<?php

require_once __DIR__.'/../../vendor/autoload.php';


$client = (new \Bunny\Client(['host' => 'rabbit']))->connect();
$channel = $client->channel();

$channel->exchangeDeclare('process_order', 'direct');

$queue = $channel->queueDeclare('process_order_tomorrow');
$channel->queueBind($queue->queue, 'process_order', 'delivery-tomorrow');

$queue = $channel->queueDeclare('process_order_later');
$channel->queueBind($queue->queue, 'process_order', 'delivery-later');

$priorities = [
    'delivery-tomorrow',
    'delivery-later',
];

for ($i = 0; $i < 10; $i++) {
    $priority = $priorities[rand(0, 1)];
    $data = sprintf("Message %d", $i+1);

    $channel->publish($data, [], 'process_order', $priority);
    echo sprintf(' [x] Sent %s : %s', $priority, $data) . PHP_EOL;
}

$channel->close();
$client->disconnect();