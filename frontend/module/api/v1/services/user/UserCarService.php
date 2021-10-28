<?php

namespace frontend\module\api\v1\services\user;

use common\models\Car;
use common\models\CarUser;
use common\models\Garage;
use common\models\User;
use Codeception\Util\Debug;
use yii\db\Query;

class UserCarService extends UserServiceBase
{
    public function has(Car $car): bool
    {
        return null !== CarUser::findOne(['car_id' => $car->id, 'user_id' => $this->user->id]);
    }

    public function all()
    {
        return (new Query())
            ->select([
                'cars.id',
                'model',
                'modification',
                'price',
                'model',
                'speed',
                "IF (cars_users.user_id = {$this->user->id}, speed_bonus, null) as 'speed_bonus'",
                'mobility',
                "IF (cars_users.user_id = {$this->user->id}, mobility_bonus, null) as 'mobility_bonus'",
                'brake',
                "IF (cars_users.user_id = {$this->user->id}, brake_bonus, null) as 'brake_bonus'",
                "(cars_users.user_id = {$this->user->id} and cars_users.user_id IS NOT NULL) as 'has_car'",
                'img_active',
                'img_inactive'
            ])
            ->from('cars')
            ->leftJoin('cars_users', 'cars_users.car_id = cars.id')
            ->groupBy([
                'model',
                'modification',
                'price',
                'cars.id',
                'user_id',
                'model',
                'speed',
                'speed_bonus',
                'mobility',
                'mobility_bonus',
                'brake',
                'brake_bonus',
                'has_car',
                'img_active',
                'img_inactive'
            ])
            ->all();
    }

    public function allFromGarage(Garage $garage)
    {
        $query = (new Query());
        return (new Query())
            ->select([
                'cars.id',
                'model',
                'modification',
                'price',
                'speed',
                "IF (cars_users.user_id = {$this->user->id}, speed_bonus, null) as 'speed_bonus'",
                'mobility',
                "IF (cars_users.user_id = {$this->user->id}, mobility_bonus, null) as 'mobility_bonus'",
                'brake',
                "IF (cars_users.user_id = {$this->user->id}, brake_bonus, null) as 'brake_bonus'",
                "(cars_users.user_id = {$this->user->id} and cars_users.user_id IS NOT NULL) as 'has_car'",
                'img_active',
                'img_inactive'
            ])
            ->from('cars')
            ->innerJoin('garages', 'cars.garage_id = garages.id')
            ->leftJoin('cars_users', 'cars_users.car_id = cars.id')
            ->where([
                'garages.id' => $garage->id
            ])
            ->all();
    }
}