<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "garages_users".
 *
 * @property int $id
 * @property int $garage_id
 * @property int $user_id
 *
 * @property Garages $garage
 * @property Users $user
 */
class GarageUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'garages_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['garage_id', 'user_id'], 'required'],
            [['garage_id', 'user_id'], 'integer'],
            [['user_id', 'garage_id'], 'unique', 'targetAttribute' => ['user_id', 'garage_id']],
            [['garage_id'], 'exist', 'skipOnError' => true, 'targetClass' => Garages::className(), 'targetAttribute' => ['garage_id' => 'id']],
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
            'garage_id' => 'Garage ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Garage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGarage()
    {
        return $this->hasOne(Garages::className(), ['id' => 'garage_id']);
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
