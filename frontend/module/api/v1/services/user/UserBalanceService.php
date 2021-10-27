<?php

namespace frontend\module\api\v1\services\user;



class UserBalanceService extends UserServiceBase
{

    public function has($money) :bool
    {
        return $this->user->TRX >= $money;
    }

    public function add($money) :bool
    {
        if (!true){
            return false;
        }
        $this->user->TRX += $money;
        return true;
    }
}