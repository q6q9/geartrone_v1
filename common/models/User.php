<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $address
 * @property int $rank
 *
 * @property Car[] $cars
 * @property CarUser[] $carsUsers
 * @property Garage[] $garages
 * @property GarageUser[] $garagesUsers
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'rank'], 'required'],
            [['rank'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['address'], 'unique'],
            [['rank'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'rank' => 'Rank',
        ];
    }

    /**
     * Gets query for [[Cars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return $this->hasMany(Car::class, ['id' => 'car_id'])->viaTable('cars_users', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CarsUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarsUsers()
    {
        return $this->hasMany(CarUser::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Garages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGarages()
    {
        return $this->hasMany(Garage::class, ['id' => 'garage_id'])->viaTable('garages_users', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[GaragesUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGaragesUsers()
    {
        return $this->hasMany(GarageUser::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['address' => $token]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
