<?php
namespace app\controllers;
use app\models\Cars;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use Yii;
use yii\base\ErrorException;
use yii\web\UploadedFile;
/**
 * UserController implements the CRUD actions for User model.
 */
class CarsController extends FunctionController
{
    public $modelClass = 'app\models\Cars';
    public function actionSearch($brand)
    {
        $cars = Cars::getByBrand();
        if ($cars->brand == $brand) {
            return $this->send(200, ['data' => Cars::findAll($brand)]);
        }
        return $this->send(204,null);
    }
    public function actionCreate()
    {
        $data=Yii::$app->request->post();
        $cars = new Cars;
        $user = User::getByToken();
        if ($user && $user->isAuthorized() && $user->admin == 1) {
        $cars->load($data, '');
        if (!$cars->validate()) return $this -> validation($cars);
        $cars->photo = UploadedFile::getInstanceByName('photo');
        $hash =hash('sha256', $cars->photo->basename);
        $cars->photo->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
        $cars->photo=$hash;
        $cars->save(false);
        if (UploadedFile::getInstanceByName('photo2')){
            $cars->photo2 = UploadedFile::getInstanceByName('photo2');
            $hash =hash('sha256', $cars->photo2->basename);
            $cars->photo2->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
            $cars->photo2=$hash;    
        }
        if (UploadedFile::getInstanceByName('photo3')){
            $cars->photo3 = UploadedFile::getInstanceByName('photo3');
            $hash =hash('sha256', $cars->photo3->basename);
            $cars->photo3->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
            $cars->photo3=$hash;    
        }
        $cars->save(false);
        return $this->send(201,"Машина успешно добавлена");
    }
    else{
        return $this->accessValidation($cars);
    }
    }
    public function actionDelete($id_car)
    {
    $user = User::getByToken();
    $car = Cars::findOne($id_car);
    if($user->isAuthorized() && $user->admin == 1 ){
    if(!empty($car -> id_car)){
    $car -> delete();
    return $this->send(200,"Машина успешна удалена");
    }
    else{
        return $this->send(404,['error'=>['code'=>404, 'message'=>'Not found', 'errors'=>['Запрашиваемая машина не найдена']]]);
    }
    }
    else{
        return $this->accessValidation($car);;
    }
    }
    public function actionRedact($id_car){
        $user = User::getByToken();
        if($user->isAuthorized() && $user->admin == 1 ){
        $car = Cars::findOne($id_car);
        $car->load(Yii::$app->request->bodyParams, '') ;
        var_dump(Yii::$app->request->bodyParams, '');
        die;
        if (!$car->validate()) return $this -> validation($car);
        if(UploadedFile::getInstanceByName('photo')){
        $car->photo = UploadedFile::getInstanceByName('photo');
        $hash =hash('sha256', $car->photo->basename);
        $car->photo->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
        $car->photo=$hash;
        };
        if(UploadedFile::getInstanceByName('photo2'))
        {
            $car->photo2 = UploadedFile::getInstanceByName('photo2');
            $hash =hash('sha256', $car->photo2->basename);
            $car->photo2->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
            $car->photo2=$hash;    
        };
        if(UploadedFile::getInstanceByName('photo3')){
            $car->photo3 = UploadedFile::getInstanceByName('photo3');
            $hash =hash('sha256', $car->photo3->basename);
            $car->photo3->saveAs(Yii::$app->basePath. '/web/assets/upload/' .$hash).
            $car->photo3=$hash;    
        };
        $car->save(false);
        return $this -> send(200, "Данные машины успешно изменены");
        }
        else{
            return $this->accessValidation($car);;;
        }
    }
    public function actionAll(){
    $cars = Cars::find()->indexBy('id_car')->all();
    return $this->send(200, ['data' => $cars]);
    }

    public function actionBrand(){
        $car = new Cars;
        $car= Cars::findAll(['brand' => Yii::$app->request->get('brand')]);
        return $this->send(200, ['data' => $car]);
        }

    public function actionCategory(){
        $car = new Cars;
        $car= Cars::findAll(['category' => Yii::$app->request->get('category')]);
        return $this->send(200, ['data' => $car]);
        }
}
