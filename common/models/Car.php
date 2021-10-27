<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cars".
 *
 * @property int $id
 * @property int $garage_id
 * @property string $model
 * @property string|null $modification
 * @property int $price
 * @property int $speed
 * @property int $mobility
 * @property int $brake
 *
 * @property CarsUsers[] $carsUsers
 */
class Car extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('cars_users', ['car_id' => 'id']);
    }

    public function getGarage() {
        return $this->hasOne(Garage::class, ['id' => 'garage_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['garage_id', 'model', 'price', 'speed', 'mobility', 'brake'], 'required'],
            [['garage_id', 'price', 'speed', 'mobility', 'brake'], 'integer'],
            [['model', 'modification'], 'string', 'max' => 32],
            [['garage_id'], 'exist', 'skipOnError' => true, 'targetClass' => Garages::className(), 'targetAttribute' => ['garage_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'garage_id' => 'Garage ID',
            'model' => 'Model',
            'modification' => 'Modification',
            'price' => 'Price',
            'speed' => 'Speed',
            'mobility' => 'Mobility',
            'brake' => 'Brake',
        ];
    }

    /**
     * Gets query for [[CarsUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarsUsers()
    {
        return $this->hasMany(CarsUsers::className(), ['car_id' => 'id']);
    }
}
