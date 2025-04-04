<?php 
//-----Basic Routing for redirection based on URI matching-----//

## 1st Approach

//--directory path of the currently executing script relative to the document root--//
$relativePath = dirname($_SERVER['SCRIPT_NAME']) ;

//-----trimming $relativePath & getting only URI-----//
$request = str_replace($relativePath,'',$_SERVER['REQUEST_URI']);
$request = ltrim($request,'/');

//----Parse the request to separate path and query param----//
$parsedUrl = parse_url($request);
$request = $parsedUrl['path'];

//----Defination of Route Mappings----//
$routes = [
    '' => 'views/index.php',
    'forget-password'=> 'views/forgetpass.php',
    'reset-password' => 'views/resetPass.php',
    'member-creation' => 'views/create_members.php',
    'member-profile' => 'views/account.php',
    'order-history' => 'views/orders.php',
    'subscribed-orders' => 'views/subscriptions.php',
    'logout' => 'views/logout.php'
];

if (array_key_exists($request, $routes)) {
    require __DIR__ . '/' . $routes[$request]; 
} else {
    http_response_code(404);
    require __DIR__ . '/views/404.php'; 
}



## 2nd Approach

// //--directory path of the currently executing script relative to the document root--//
// $relativePath = dirname($_SERVER['SCRIPT_NAME']) ;

// //-----trimming $relativePath & getting only URI-----//
// $request = $_SERVER['REQUEST_URI'];
// $request = str_replace($relativePath, '', $request);

// $viewDir = 'views/';

// switch ($request) {

//     case '' :
//     case '/':
//         require __DIR__ .'/'. $viewDir . 'index.php';
//         break;

//     case '/forget-password':
//         require __DIR__ .'/'. $viewDir . 'forgetpass.php';
//         break;

//     case '/reset-password':
//         require __DIR__ .'/'. $viewDir . 'resetPass.php';
//         break;

//     case '/member-creation':
//         require __DIR__ .'/'. $viewDir . 'create_members.php';
//         break;

//     case '/member-profile':
//         require __DIR__ .'/'. $viewDir . 'account.php';
//         break;    

//     case '/order-history':
//         require __DIR__ .'/'. $viewDir . 'orders.php';
//         break;

//     case '/subscribed-orders':
//         require __DIR__ .'/'. $viewDir . 'subscriptions.php';
//         break; 

//     case '/logout':
//         require __DIR__ .'/'. $viewDir . 'logout.php';
//         break;        
        
//     default:
//         http_response_code(404);
//         require __DIR__ .'/'. $viewDir . '404.php';

// }


?>