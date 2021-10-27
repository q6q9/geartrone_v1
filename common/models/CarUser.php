<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cars_users".
 *
 * @property int $id
 * @property int $car_id
 * @property int $user_id
 * @property int|null $speed_bonus
 * @property int|null $mobility_bonus
 * @property int|null $brake_bonus
 * @property int|null $level
 *
 * @property Car $car
 * @property User $user
 */
class CarUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'user_id'], 'required'],
            [['car_id', 'user_id', 'speed_bonus', 'mobility_bonus', 'brake_bonus', 'level'], 'integer'],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cars::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Car ID',
            'user_id' => 'User ID',
            'speed_bonus' => 'Speed Bonus',
            'mobility_bonus' => 'Mobility Bonus',
            'brake_bonus' => 'Brake Bonus',
            'level' => 'Level',
        ];
    }

    /**
     * Gets query for [[Car]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Cars::className(), ['id' => 'car_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
