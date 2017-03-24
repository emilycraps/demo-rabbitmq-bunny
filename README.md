# RabbitMQ demo using bunny

**RabbitMQ** https://www.rabbitmq.com/

**bunny** https://github.com/jakubkulhan/bunny

## Setup

`$ composer install`

## Run demo
This demo uses docker and docker-compose.

`$ docker-compose up`

You can access the RabbitMQ management plugin at http://rabbit.bunny.docker/. Default login is guest / guest.

### Confirm Order example
Demonstrates the most simple example: 1 exchange, 1 queue, 1 consumer.

Publish 10 messages  
`$ docker-compose run --rm web php src/ConfirmOrder/publisher.php`

Start consumer  
`$ docker-compose run --rm web php src/ConfirmOrder/consumer.php`

### Process Order example
Demonstrates routing keys

Publish 10 messages  
`$ docker-compose run --rm web php src/ProcessOrder/publisher.php`

Start consumers  
`$ docker-compose run --rm web php src/ProcessOrder/consumer-later.php`  
`$ docker-compose run --rm web php src/ProcessOrder/consumer-tomorrow.php`

### Dead Letter Exchange example
Demonstrates how to route rejected messages to a dead letter exchange.

Publish 10 messages  
`$ docker-compose run --rm web php src/DeadLetterExchange/publisher.php`

Start consumer  
`$ docker-compose run --rm web php src/DeadLetterExchange/consumer.php`