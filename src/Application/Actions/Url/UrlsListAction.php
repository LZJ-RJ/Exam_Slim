<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use mysqli;

final class UrlsListAction
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


    public function list_urls(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $db = $this->connect_db();
        $show_result =
            '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">'.
            '<table class="table table-dark table-bordered">
                   <thead>
                       <tr>
                           <th>ID</th>
                           <th>Source</th>
                           <th>Destination</th>
                           <th>Count</th>
                           <th>Valid Time</th>
                       </tr>
                   </thead>
               <tbody>';

        $db_result = $db->query( 'SELECT id, source, destination, count, valid_time FROM urls;' );
        while ( $row = $db_result->fetch_array(MYSQLI_ASSOC) ) {
            $show_result.= '<tr>';
            $show_result .= '<td>'.$row['id'].'</td>';
            $show_result .= '<td>'.$row['source'].'</td>';
            $show_result .= '<td>'.$row['destination'].'</td>';
            $show_result .= '<td>'.$row['count'].'</td>';
            $show_result .= '<td>'.$row['valid_time'].'</td>';
            $show_result.= '</tr>';
        }

        $show_result .= '</tbody></table>';
        $result = $show_result;
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'){
            $http_text = 'https://';
        }else{
            $http_text = 'http://';
        }
        $form_result = '<form method="post" action="'.$http_text.$_SERVER['HTTP_HOST'].'/url">
<input type="text" style="width:40%;" name="destination" placeholder="Please type your url that would be replaced.">
<input type="submit" class="btn btn-info" >
</form>';
        $result .= $form_result;

        $response->getBody()->write($result);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(201);
    }
}