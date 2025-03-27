<?php 
//-----Basic Routing for redirection of URI on matching-----//

$request = $_SERVER['REQUEST_URI'];
$viewDir = 'views/';

switch ($request) {

    case '':
    case '/':
        require __DIR__ .'/'. $viewDir . 'index.php';
        break;

    case '/forget-password':
        require __DIR__ .'/'. $viewDir . 'forgetpass.php';
        break;

    case '/reset-password':
        require __DIR__ .'/'. $viewDir . 'resetPass.php';
        break;

    case '/member-creation':
        require __DIR__ .'/'. $viewDir . 'create_members.php';
        break;

    case '/member-profile':
        require __DIR__ .'/'. $viewDir . 'account.php';
        break;    

    case '/order-history':
        require __DIR__ .'/'. $viewDir . 'orders.php';
        break;

    case '/subscribed-orders':
        require __DIR__ .'/'. $viewDir . 'subscriptions.php';
        break; 

    case '/logout':
        require __DIR__ .'/'. $viewDir . 'logout.php';
        break;        
        
    default:
        http_response_code(404);
        require __DIR__ .'/'. $viewDir . '404.php';
        
}


?>