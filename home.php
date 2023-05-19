<?php 

$email = '';
$name = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['email'])) {
        http_response_code(502);
        print json_encode(["message" => "Email required"]);
    }
}

