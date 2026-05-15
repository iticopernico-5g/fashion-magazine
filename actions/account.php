<?php
require_once __DIR__ . '/../camezilla/camezilla.php';

use App\Models\Role;
use App\Services\AuthenticationService;
use Camezilla\Dispatchers\Dispatcher;
use App\Models\User;
use App\Services\UserService;

$dispatcher = new Dispatcher(page('not-found.php'), page('error.php'));
$userService = new UserService();
$authenticationService = new AuthenticationService();

$dispatcher->post('register', function($params) use ($authenticationService) {
    $user = new User (null, $params['first_name'], $params['last_name'], $params['email'], $params['password'], Role::from($params['role']));

    try {
        $authenticationService->register($user);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
        Dispatcher::error_go_back($e->getMessage());
    }
});

$dispatcher->post('login', function($params) use ($authenticationService) {
    $user = new User (null, null, null, $params['email'], $params['password'], null);

    try {
        $authenticationService->login($user);
        Dispatcher::ok_redirect();
    } catch (Exception $e) {
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