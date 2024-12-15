<?php

namespace app\controllers;

use app\models\TestDrive;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use Yii;
use app\models\Cars;

/**
 * UserController implements the CRUD actions for User model.
 */
class TestDriveController extends FunctionController
{
    public $modelClass = 'app\models\TestDrive';
    public function actionCreate()
    {
        $data=Yii::$app->request->post();
        $TestDrive=new TestDrive();
        $TestDrive->load($data, '');
        $car = new Cars();
        $car = Cars::find()->where(['id_car' => $TestDrive -> car_id])->one();
        if(is_null($car)) return $this -> send(404, ['error'=>['code'=>404, 'message'=>'Not Found', 'errors'=>['Нужная машина сейчас недоступна']]]);
        if (!$TestDrive->validate()) return $this -> validation($TestDrive);
        $TestDrive->save();
        return $this->send(201,"Вы успешно записаны на тест-драйв");
    }
}