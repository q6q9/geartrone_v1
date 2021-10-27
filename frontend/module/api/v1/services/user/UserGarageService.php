<?php

namespace frontend\module\api\v1\services\user;

use common\models\Garage;
use Codeception\Util\Debug;
use yii\db\Query;

class UserGarageService extends UserServiceBase
{
    public function all()
    {
        return (new Query())
            ->select([
                'garages.id',
                'name',
                'description',
                "(garages_users.user_id = {$this->user->id} and garages_users.user_id IS NOT NULL) as 'has_garage'",
            ])
            ->from('garages')
            ->leftJoin('garages_users', 'garages_users.garage_id = garages.id')
            ->groupBy([
                'garages.id',
                'name',
                'description',
                'has_garage'
            ])
            ->all();
    }
    public function has(Garage $garage) :bool {
        return $garage->getUsers()->where(['id' => $this->user->id])->count();
    }

}