<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cars".
 *
 * @property int $id_car
 * @property string $brand
 * @property string $model
 * @property string $description
 * @property string $coast
 * @property string $year
 */
class Cars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand', 'model', 'category', 'description', 'coast', 'year'], 'required'],
            [['year'], 'safe'],
            [['brand', 'model', 'description','category', 'coast','photo','photo2','photo3'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_car' => 'Id Car',
            'brand' => 'Brand',
            'model' => 'Model',
            'description' => 'Description',
            'category' => 'Category',
            'coast' => 'Coast',
            'year' => 'Year',
            'photo' => 'photo',
            'photo2' => 'photo2',
            'photo3' => 'photo3',
        ];
    }
    public static function getByBrand() {
        return self::findAll(['brand' => (Yii::$app->request->get('brand'))]);
    }
    public static function getById() {
        return self::findOne(['id' => (Yii::$app->request->get('id_car'))]);
    }
    public function getId()
    {
    return $this->id_car;
    }
}
