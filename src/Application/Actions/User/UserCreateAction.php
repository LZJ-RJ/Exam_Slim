<?php

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use mysqli;

final class UserCreateAction
{
//    private $userCreator;

    public function __construct()
    {
    }

    private function connect_db() {
        $server = 'localhost';
        $user = 'db_user';
        $pass = 'db_password';
        $database = 'db_name';
        $connection = new mysqli($server, $user, $pass, $database);

        return $connection;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();

        $db = $this->connect_db();
        if($db){
            $result = 'Connect to DB successfully.';
        }else{
            $result = 'Connect to DB failed.';
        }

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        // Invoke the Domain with inputs and retain the result
        // $userId = $this->userCreator->createUser($data);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }


    public function list_users(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();

        $db = $this->connect_db();
        $result = $db->query( 'SELECT id FROM users;' );
        while ( $row = $result->fetch_array(MYSQLI_ASSOC) ) {
            $data[] = $row;
        }

        $result = $data;

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}