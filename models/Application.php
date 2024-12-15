<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id_zakaz
 * @property int $car_id
 * @property string $FIO
 * @property string $email
 * @property string $phone
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id', 'fio', 'email', 'phone', 'oplata'], 'required'],
            [['fio'], 'match', 'pattern'=> '/[А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+/'],
            [['phone'], 'match', 'pattern'=> '/^\+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'],
            [['car_id'], 'integer'],
            [['fio', 'email', 'phone','oplata'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_zakaz' => 'Id Zakaz',
            'car_id' => 'Car ID',
            'fio' => 'Fio',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
}
