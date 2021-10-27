<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "garages".
 *
 * @property int $id
 * @property int $price
 * @property string|null $description
 *
 * @property Cars[] $cars
 * @property GaragesUsers[] $garagesUsers
 */
class Garage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'garages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'required'],
            [['price'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Cars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('garages_users', ['garage_id' => 'id']);
    }

    public function getCars()
    {
        return $this->hasMany(Car::class, ['garage_id' => 'id']);
    }


    /**
     * Gets query for [[GaragesUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGaragesUsers()
    {
        return $this->hasMany(GaragesUsers::className(), ['garage_id' => 'id']);
    }
}
