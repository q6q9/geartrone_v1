<?php

namespace frontend\module\api\v1\services\user;



use Yii;

class UserPasswordService extends UserServiceBase
{
    public function setHash($password)
    {
        $this->user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function validate($password): bool
    {
//        var_dump($this->user->password_hash);
//        die();
        return Yii::$app->getSecurity()->validatePassword($password, $this->user->password_hash);
    }

    public function imitateValidation()
    {
        Yii::$app->getSecurity()->validatePassword('imitate pass',
            '$2y$13$dkexIUnYStLDeR5NyRFHXugBxdkqGWZpdhoA77VPoue3w.o0yecD0');
    }
}