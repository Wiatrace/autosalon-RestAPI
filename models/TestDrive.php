<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "test_drive".
 *
 * @property int $id_application
 * @property string $FIO
 * @property string $phone
 * @property string $email
 * @property string $date
 */
class TestDrive extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_drive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FIO', 'phone', 'email', 'date', 'car_id'], 'required'],
            [['phone'], 'match', 'pattern'=> '/^\+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'],
            [['FIO'], 'match', 'pattern'=> '/^[А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+$/'],
            [['car_id'],'integer'],
            [['date'], 'date','when' => function($model){
                 return strtotime($model->date) < time() + 97200;
            },],
            [['FIO', 'phone', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_application' => 'Id Application',
            'FIO' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Емейл',
            'date' => 'Дата',
            'car_id' => 'car id'
        ];
    }
}
