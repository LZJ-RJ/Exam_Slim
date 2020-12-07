<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use mysqli;

final class UrlCreateAction
{

    private function connect_db() {
        $server = 'localhost';
        $user = 'db_user';
        $pass = 'db_password';
        $database = 'db_name';
        $connection = new mysqli($server, $user, $pass, $database);

        return $connection;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'){
            $http_text = 'https://';
        }else{
            $http_text = 'http://';
        }
        $randomString = $http_text.$_SERVER['HTTP_HOST'].'/redirect/'.$randomString;

        $db = $this->connect_db();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $db_result_before = $db->query( 'SELECT destination FROM urls WHERE `destination` = '.$randomString.';' );
        if($db_result_before === TRUE) {
            $randomString = $this->generateRandomString(5);
        }
        return $randomString;
    }

    public function __construct(){}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
//        $data = (array)$request->getParsedBody();
//        $db = $this->connect_db();
//        if($db){
//            $result = 'Connect to DB successfully.';
//        }else{
//            $result = 'Connect to DB failed.';
//        }

        $result = 'do nothing';
        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        // Invoke the Domain with inputs and retain the result
        // $userId = $this->userCreator->createUser($data);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }


    public function new_url(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $destination = '';
        $show_result = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">';
        if(isset($_REQUEST['destination']) && (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $_REQUEST['destination']))) {
            $destination = $_REQUEST['destination'];
            //Step1 先確認有沒有重複
            $source = $this->generateRandomString(5); // length reference : https://www.shorturl.at/
//            $nowTimeStamp = time();
            $nowTimeStamp = new \DateTime('now');
            $nowTimeStamp = strtotime($nowTimeStamp->format('Y/m/d H:i:s'));
            $db = $this->connect_db();
            if ($db->connect_error) {
                die("Connection failed: ". $db->connect_error);
            }

            //Step2 可以存進去資料庫
            $db_result = $db->query( 'INSERT INTO urls(source, destination, count, valid_time) VALUES ("'.$source.'", "'.$destination.'", "1", "'.$nowTimeStamp.'")');
            if ($db_result === TRUE) {
                $show_result .= '<h4>Successful</h4><br>';
              }else{
                $show_result .= '<h4>Fail</h4><br>';
            }
        }else{
            $show_result .= '<h4>Do nothing</h4>';
        }
        $show_result .= '<button class="btn btn-success" onclick="history.go(-1);">Redirect Back</button>';


        $response->getBody()->write($show_result);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(201);
    }
}