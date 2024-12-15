<?php
namespace app\controllers;
use yii\rest\Controller;
use yii\widgets\ActiveForm;
class FunctionController extends Controller{
    public function send($code, $data){
    $response=$this->response;
    $response->format = \yii\web\Response::FORMAT_JSON; 
    $response->data=$data;
    $response->statusCode=$code;
    return $response;
    }
    public function validation($model){
    $error=['error'=> ['code'=>422, 'message'=>'Ошибка валидации', 'errors'=>ActiveForm::validate($model)]];
    return $this->send(422, $error);
    }

    public function accessValidation($model){
    $error=['error'=> ['code'=>403, 'message'=>'Ошибка доступа']];
    return $this->send(403, $error);    
    }
    public function unauthorizedValidation($model){
    $error=['error'=> ['code'=>401, 'message'=>'Ошибка авторизации']];
    return $this->send(401, $error);       
    }
   } 
   