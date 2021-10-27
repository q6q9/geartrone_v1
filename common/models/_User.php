<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property int|null $TRX
 * @property int $rank
 * @property string|null $access_token_value
 * @property string|null $access_token_created_at
 */
class _User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public function getCars() {
        return $this->hasMany(Car::class, ['id' => 'car_id'])
            ->viaTable('cars_users', ['user_id' => 'id']);
    }
    public function getGarages() {
        return $this->hasMany(Garage::class, ['id' => 'garage_id'])
            ->viaTable('garages_users', ['user_id' => 'id']);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token_value' => $token]);
    }
    public function getId()
    {
        return $this->id;
    }

    public static function getSelf(): User
    {
        $access_token = explode(' ', Yii::$app->request->headers['authorization'])[1];
        return User::findIdentityByAccessToken($access_token);
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['password_hash'], $fields['access_token_created_at'], $fields['access_token_value'],);

        return $fields;
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'rank'], 'required'],
            [['TRX', 'rank'], 'integer'],
            [['access_token_created_at'], 'safe'],
            [['username'], 'string', 'max' => 16],
            [['password_hash'], 'string', 'max' => 60],
            [['access_token_value'], 'string', 'max' => 64],
            [['username'], 'unique'],
            [['rank'], 'unique'],
        ];
    }

    // Бесполезные методы к интерфейсу для доступа с помощью Bearer Token
    public function getAuthKey()
    {
    }
    public function validateAuthKey($authKey)
    {
    }
    public static function findIdentity($id)
    {
    }
}
