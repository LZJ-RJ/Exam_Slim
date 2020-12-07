<?php

namespace App\Application\Actions\Url;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use mysqli;


final class UrlCheckAction
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


    public function check_url(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {


        $db = $this->connect_db();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $result = '';
        $url_array = explode('/', $_SERVER['REQUEST_URI']);
        $url_id = 0;
        foreach($url_array as  $key => $parameters){
            if($parameters == 'url'){
                $url_id = $url_array[$key+1];
                break;
            }
        }

        if($url_id != 0){
            $show_result =
                '<table>
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

            $db_result = $db->query( 'SELECT id, source, destination, count, valid_time FROM urls WHERE `id` = '.$url_id.';' );
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
        }else{
            $result .= 'Fail_02 ( !isset($_REQUEST["id"]) )';
        }


        $response->getBody()->write($result);
        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(201);
    }
}