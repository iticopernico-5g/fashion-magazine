<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Models\Role;
use App\Services\AuthenticationService;
use Camezilla\Dispatchers\Dispatcher;
use App\Models\User;
use App\Services\UserService;

// Controlla se è una richiesta AJAX (fetch dal JavaScript)
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

$dispatcher = new Dispatcher(page('not-found.php'), page('error.php'));
$userService = new UserService();
$authenticationService = new AuthenticationService();

$dispatcher->post('register', function($params) use ($authenticationService, $isAjax) {
    $user = new User (null, $params['first_name'], $params['last_name'], $params['email'], $params['password'], Role::from($params['role'] ?? 'viewer'));

    try {
        $authenticationService->register($user);
        
        // Se è AJAX, ritorna JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'ok' => true,
                'message' => 'Registrazione completata',
                'email' => $user->get_email()
            ]);
            exit();
        }
        
        // Altrimenti usa il Dispatcher normale
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
            exit();
        }
        
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->post('login', function($params) use ($authenticationService, $isAjax) {
    $user = new User (null, null, null, $params['email'], $params['password'], null);

    try {
        $authenticationService->login($user);
        
        // Leggi il ruolo dalla sessione (salvato da AuthenticationService)
        $role = get_session_item('authentication.role') ?? 'viewer';
        $email = $params['email'];
        
        // Determina la pagina di redirect
        $redirect = page('index.php'); // default
        
        if ($role === 'admin') {
            $redirect = 'pages/users.php';
        } elseif ($role === 'student') {
            $redirect = 'gestione_articoli.php';
        }
        
        // Se è AJAX (richiesta fetch da JavaScript), ritorna JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'ok' => true,
                'message' => 'Login riuscito',
                'email' => $email,
                'role' => $role,
                'redirect' => $redirect
            ]);
            exit();
        }
        
        // Altrimenti usa il Dispatcher normale (reindirizza)
        header("Location: $redirect");
        exit();
        
    } catch (Exception $e) {
        if ($isAjax) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
            exit();
        }
        
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->get('logout', function() use ($authenticationService) {
    try {
        $authenticationService->logout();
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->post('update', function($params) use ($userService) {
    $user = new User ($params['id'], $params['first_name'], $params['last_name'], $params['email'], null, Role::from($params['role']));

    try {
        $userService->update($user);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->dispatch();