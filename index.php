<?php

use aminshokripwa\Download\Download as Download;
use aminshokripwa\Api\Api as Api;

require_once './app/bootstrap.php';

$url = "https://api.github.com/users/aminshokripwa/repos";
$_action =  $_REQUEST['_action'] ?? $_SERVER["CONTENT_TYPE"] ?? null ;

$api = new Api();
$users = $api->getUsers($url);

switch ($_action) {
    case 'csv':
    case 'text/csv':
        if (isset($users) && count($users) > 0) {
            header('Content-Type: text/csv; charset=UTF-8');
            $dl = new Download($users, 'csv');
            $dl->serveFileToDownload();
        } else {
            print(json_encode(['error' => 'invalid_data', 'error_description' => 'Users Result Not set']));
        }
        break;
    case 'json':
    case 'application/json':
        if (isset($users) && count($users) > 0) {
            header("Content-Type: application/json; charset=UTF-8");
            $dl = new Download($users, 'json');
            $dl->serveFileToDownload();
        } else {
            print(json_encode(['error' => 'invalid_data', 'error_description' => 'Users Result Not set']));
        }
        break;
    case 'html':
    case 'text/html':
        if (isset($users) && count($users) > 0) {
            header("Content-Type: text/html; charset=UTF-8");
            $dl = new Download($users, 'html');
            $dl->serveFileToDownload();
        } else {
            print(json_encode(['error' => 'invalid_data', 'error_description' => 'Users Result Not set']));
        }
        break;
    default:
        if (isset($users) && count($users) > 0) {
            echo '<html><style>table, th, td {border:0.5px dotted black;}</style><body><table style="width:100%;text-align: left;">
        <tr>
          <th>id</th>
          <th>name</th>
          <th>description</th>
        </tr>';
            foreach ($users as $user_data) {
                echo '<tr>';
                echo '<td>'.$user_data['id'].'</td>';
                echo '<td>'.$user_data['name'].'</td>';
                echo '<td>'.$user_data['description'].'</td>';
                echo '</tr>';
            }
            echo '</table></body></html>'; ?>
        <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]; ?>?_action=csv';">Export as csv</button>
        <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]; ?>?_action=json';">Export as json</button>
        <button type="button" onclick="location.href='<?=$_SERVER["PHP_SELF"]; ?>?_action=html';">Export as html</button>
        <?php
        } else {
            print_r(json_encode($users, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        break;
}
