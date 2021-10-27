<?php

namespace frontend\module\api\v1\services;



use common\models\User;
use frontend\module\api\v1\models\AuthModel;

class AuthService
{
    protected $authModel;
    protected $userService;

    public $errors;
    public $authKey;

    public function __construct(AuthModel $auth, UserService $userService)
    {
        $this->authModel = $auth;
        $this->userService = $userService;
    }


    public function register(): bool
    {
        if (!$this->authModel->validate()) {
            $this->errors = $this->authModel->errors;
            return false;
        }
        if (User::findOne(['username' => $this->authModel->username])) {
            $this->errors = 'Пользователь с таким именем уже существует';
            return false;
        }

        $this->userService->getUser()->username = $this->authModel->username;
        $this->userService->password->setHash($this->authModel->password);
        $this->userService->rank->setDown();

        $this->userService->getUser()->save(true);
        return true;
    }


    public function login(): bool
    {
        if (!$this->authModel->validate()) {
            $this->errors = $this->authModel->errors;
            return false;
        }

        $this->userService->setUser(User::findOne(['username' => $this->authModel->username]));
        if ($this->userService->getUser() && $this->userService->password->validate($this->authModel->password)) {
            if (!$this->userService->authKey->isActive()) {
                $this->userService->authKey->setGenerated();
                $this->userService->getUser()->save(false);
            }
            $this->authKey = $this->userService->getUser()->access_token_value;
            return true;
        }

        $this->errors = 'Неверные имя или пароль';
        if (!$this->userService->getUser())
            $this->userService->password->imitateValidation();
        return false;
    }

}