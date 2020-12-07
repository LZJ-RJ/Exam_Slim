<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use mysqli;


final class UrlRedirectAction
{

    public function __construct(){}

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


    public function redirect_url(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {


        $db = $this->connect_db();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $result = '';
        $url_array = explode('/', $_SERVER['REQUEST_URI']);
        $source = '';
        if(!empty($url_array)){
            foreach($url_array as $index => $value){
                if($value == 'redirect'){
                    $source = $url_array[$index+1];
                    break;
                }
            }

            if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'){
                $http_text = 'https://';
            }else{
                $http_text = 'http://';
            }
            $source = $http_text.$_SERVER['HTTP_HOST'].'/redirect/'.$source;
        }

        $redirect_url = '';
        if($source != ''){
            $db_result = $db->query( 'SELECT source, destination FROM urls WHERE `source` = "'.$source.'";' );
            while ( $row = $db_result->fetch_array(MYSQLI_ASSOC) ) {
                $redirect_url = $row['destination'];
                $result = 'Successful';
                break;
            }
        }else{
            $result .= 'Fail ( $source == "" )';
        }

        $result .= $source;
        $response->getBody()->write($result);

        return $response
            ->withHeader('Location', $redirect_url)
            ->withStatus(   302);
    }
}