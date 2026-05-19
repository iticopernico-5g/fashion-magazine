<?php

namespace App\Services;

use App\Models\User;
use App\Services\UserService;
use Camezilla\Exceptions\ServiceErrorException;
use Camezilla\Services\Service;
use Exception;

class AuthenticationService extends Service {

    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function authenticate(User $user) {
        if (!$user->get_email() || !$user->get_password_hash()) {
            throw new ServiceErrorException(t("service.authentication.error.missing_credentials"));
        }

        try {
            $existing_user = $this->userService->get_by_email($user->get_email());
            if (!$existing_user) {
                throw new ServiceErrorException(t("service.authentication.error.not_found"));
            }

            if (!password_verify($user->get_password_hash(), $existing_user->get_password_hash())) {
                throw new ServiceErrorException(t("service.authentication.error.invalid_credentials"));
            }

            authenticate_user($existing_user->get_id(), $existing_user->get_email());
        } catch (Exception $e) {
            if ($e instanceof ServiceErrorException) {
                throw $e;
            }

            throw new ServiceErrorException(t("service.authentication.error.authenticate"));
        }
    }

    public function register(User $user) {
        try {
            $this->userService->validate_user($user);
            $user->hash_password();
            $this->userService->create($user);

            $created_user = $this->userService->get_by_email($user->get_email());
            if (!$created_user) {
                throw new ServiceErrorException(t("service.authentication.error.not_found"));
            }

            authenticate_user($created_user->get_id(), $created_user->get_email());
        } catch (Exception $e) {
            if ($e instanceof ServiceErrorException) {
                throw $e;
            }
            
            throw new ServiceErrorException(t("service.authentication.error.register"));
        }
    }

    public function login(User $user) {
        try {
            $this->authenticate($user);
        } catch (Exception $e) {
            if ($e instanceof ServiceErrorException) {
                throw $e;
            }
            
            throw new ServiceErrorException(t("service.authentication.error.login"));
        }
    }

    public function logout() {
        try {
            remove_user_authentication();
        } catch (Exception $e) {
            if ($e instanceof ServiceErrorException) {
                throw $e;
            }
            
            throw new ServiceErrorException(t("service.authentication.error.logout"));
        }
    }
}