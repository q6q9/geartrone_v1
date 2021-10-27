<?php

namespace frontend\module\api\v1\services;

use common\models\User;
use frontend\module\api\v1\services\user\UserAuthKeyService;
use frontend\module\api\v1\services\user\UserBalanceService;
use frontend\module\api\v1\services\user\UserCarService;
use frontend\module\api\v1\services\user\UserGarageService;
use frontend\module\api\v1\services\user\UserPasswordService;
use frontend\module\api\v1\services\user\UserRankService;
use Exception;
use Yii;

class UserService
{
    protected $user;

    public $car;
    public $garage;
    public $balance;
    public $password;
    public $authKey;
    public $rank;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->car = new UserCarService($this->user);
        $this->garage = new UserGarageService($this->user);
        $this->balance = new UserBalanceService($this->user);
        $this->password = new UserPasswordService($this->user);
        $this->authKey = new UserAuthKeyService($this->user);
        $this->rank = new UserRankService($this->user);
    }

    public function setUser(User $user)
    {
        $this->__construct($user);
    }

    public function getUser()
    {
        return $this->user;
    }

    public static function getSelf(): User
    {
        $access_token = explode(' ', Yii::$app->request->headers['authorization'])[1];
        return User::findIdentityByAccessToken($access_token);
    }
}