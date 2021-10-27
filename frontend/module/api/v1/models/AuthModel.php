<?php

namespace frontend\module\api\v1\models;

class AuthModel extends \yii\base\Model
{
    public $username;
    public $password;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username'], 'string', 'max' => 16, 'min' => 4],
            [['password'], 'string', 'max' => 32, 'min' => 8],
        ];
    }
}