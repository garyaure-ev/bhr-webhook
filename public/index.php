<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Logger.php';
require __DIR__ . '/../src/WebhookHandler.php';

$app = AppFactory::create();

// Get logger instance
$logger = getLogger();

$app->addBodyParsingMiddleware(); // Required for JSON parsing

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(['status' => 'API ready.']));
    return $response->withHeader('Content-Type', 'application/json');
});
$app->post('/webhook', function (Request $request, Response $response) use ($logger) {
    $contentType = $request->getHeaderLine('Content-Type');

    // Log incoming request content type
    $logger->info('Received request', ['Content-Type' => $contentType]);

    if (stripos($contentType, 'application/json') === false) {
        $logger->warning('Invalid content type', ['Content-Type' => $contentType]);

        $response->getBody()->write(json_encode(['error' => 'Only JSON supported']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $data = $request->getParsedBody();

    // Log incoming JSON data
    $logger->info('Parsed JSON body', ['data' => $data]);

    if (!is_array($data)) {
        $logger->error('Invalid JSON format', ['error' => 'Expected array', 'data' => $data]);

        $response->getBody()->write(json_encode(['error' => 'Invalid JSON']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    // Call the webhook handler
    $result = handleWebhook($data, $logger); // Pass logger to handler for better log context

    // Log result of the webhook handler
    $logger->info('Webhook processed', ['result' => $result]);

    $response->getBody()->write(json_encode(['status' => $result]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
