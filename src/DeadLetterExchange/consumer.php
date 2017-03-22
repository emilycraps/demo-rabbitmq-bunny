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


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$channel->run(
    function (\Bunny\Message $message, \Bunny\Channel $channel, \Bunny\Client $client) {
        echo sprintf(' [x] : %s', $message->content) . PHP_EOL;
        $channel->reject($message, false);
    },
    $queue->queue
);