<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use app\models\LoginForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends FunctionController
{
    public $modelClass = 'app\models\User';
    public function actionRegister()
    {
        $data=Yii::$app->request->post();
        $user=new User(['scenario' => User::SCENARIO_DEFAULT]);
        $user->load($data, '');
        if (!$user->validate()) return $this -> validation($user);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
        $user->save(false);
        return $this->send(201,'Регистрация прошла успешно');
    }

    public function actionLogin(){
        $data=Yii::$app->request->post();
        $login_data=new LoginForm();
        $login_data->load($data, '');
       if (!$login_data->validate()) return $this->validation($login_data);
        $user=User::find()->where(['email'=>$login_data->email])->one();
        if (!is_null($user)) {
        if (\Yii::$app->getSecurity()->validatePassword($login_data->password, $user->password)) {
        $token = \Yii::$app->getSecurity()->generateRandomString();
        $user->token = $token;
        $user->save(false);
        $data = ['data' => ['token' => $token]];
        return $this->send(200, $data);
        }
        }
        else
        {
            return $this->unauthorizedValidation($login_data);
        }
        }
    
    public function actionProfile(){
    $user = User::getByToken();
    if ($user && $user->isAuthorized()) {
        return $this->send(200, ['data' => User::findOne($user -> id_user)]);
    }
    return $this->unauthorizedValidation($user);
    }

    public function actionPhone(){
    $user = User::getByToken();
    $new_user = new User(['scenario' => User::SCENARIO_UPDATE]);
    if($user && $user->isAuthorized()){
        $user = User::find()->where(['token'=>$user['token']])->one();
        $phone = Yii::$app->request->getBodyParam('phone');
        $new_user -> phone = $phone;
        if(!$new_user->validate()){
        return $this-> send(422,['error'=> ['code'=>422, 'message'=>'Ошибка валидации', 'errors'=>'Некорректно введен номер телефона']]);
        }
        $user -> phone = $phone;
        $user->update(false);
        return $this->send(200,'Номер телефона успешно изменен');
    }
    return $this->unauthorizedValidation($user);;
    }

    public function actionEmail(){
        $user = User::getByToken();
        $new_user = new User(['scenario' => User::SCENARIO_UPDATE_EMAIL]);
        if($user && $user->isAuthorized()){
            $user = User::find()->where(['token'=>$user['token']])->one();
            $email = Yii::$app->request->getBodyParam('email');
            $new_user -> email = $email;
            if(!$new_user->validate()){
            return $this-> send(422,['error'=> ['code'=>422, 'message'=>'Ошибка валидации', 'errors'=>'Некорректно введена электронная почта']]);
            }
            $user -> email = $email;
            $user->update(false);
            return $this->send(200,"Адрес электронной почты успешно изменен");
        }
        return $this->unauthorizedValidation($user);
        }
}
