<?php

namespace frontend\module\api\v1\services\user;

use common\models\User;

class UserServiceBase
{
    protected $user;

    public function __construct(User $user)
    {
            $this->user = $user;
    }
}