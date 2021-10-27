<?php

namespace frontend\module\api\v1\services\user;



use Yii;

class UserAuthKeyService extends UserServiceBase
{

    public function isActive(): bool
    {
        return $this->user->access_token_value && $this->isActuallyDateAuthKey();
    }

    public function setGenerated()
    {
        $this->user->access_token_value = Yii::$app->getSecurity()->generateRandomString(64);
        $this->user->access_token_created_at = date('Y-m-d');
    }

    private function isActuallyDateAuthKey(): bool
    {
        $unixSevenDays = 604800;
        return time() < (strtotime($this->user->access_token_created_at) + 3600*24*7);
    }
}