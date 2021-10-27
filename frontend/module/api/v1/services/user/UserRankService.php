<?php

namespace frontend\module\api\v1\services\user;

use common\models\User;

class UserRankService extends UserServiceBase
{
    public function setDown()
    {
        if ($userDownRank = User::find()->orderBy(['rank' => SORT_DESC])->one())
            $this->user->rank = $userDownRank['rank'] + 1;
        else
            $this->user->rank = 1;
    }
}