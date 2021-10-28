<?php

namespace frontend\module\api\v1\services;

use common\models\Car;
use common\models\Garage;
use common\models\User;
use common\models\CarUser;
use Codeception\Util\Debug;
use phpDocumentor\Reflection\Utils;
use Yii;

class StoreCarService
{
    public $userService;
    public $car;

    public $errors;

    public function __construct(UserService $userService, Car $car)
    {
        $this->car = $car;
        $this->userService = $userService;
    }

    public function buyCar()
    {
        if (!$this->car) {
            return false;
        }
        if (!$this->userService->getUser()) {
            $this->errors = 'Несуществующий пользователь';
            return false;
        }
        if ($this->userService->car->has($this->car)){
            $this->errors = 'Данный товар уже имеется';
            return false;
        }

        $garage = Garage::findOne($this->car->garage_id);

        if (!$this->userService->garage->has($garage)){
            $this->userService->getUser()->link('garages', $garage);
        }

        $this->userService->getUser()->update(false);
        $this->userService->getUser()->link('cars', $this->car);
        return true;
    }
}