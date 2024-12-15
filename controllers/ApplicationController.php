<?php

namespace app\controllers;

use app\models\Application;
use app\models\User;
use app\models\Cars;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class ApplicationController extends FunctionController
{
    public $modelClass = 'app\models\Application';
    public function actionReserve($id_car)
    {
        $car = Cars::find()->where(['id_car'=>$id_car])->one();
        $user = User::getByToken();
        if(!$user){
            return $this->unauthorizedValidation($user);
        }
        if(!$car){
            return $this -> send(404, ['error'=>['code'=>404, 'message'=>'Not Found', 'errors'=>['Нужная машина сейчас недоступна']]]);
        }
        if($user->isAuthorized()){
        $data=Yii::$app->request->post();   
        $data['car_id'] = $id_car; 
        $application=new Application();
        $application->load($data, '');
        if (!$application->validate()) return $this -> validation($application);
        $application->save();
        return $this->send(201, "Заявка успешно создана");
        }
    }
    }