<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "user".
 *
 * @property int $id_user
 * @property string $fio
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property int $admin
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'email', 'phone', 'password'], 'required','on' => self::SCENARIO_DEFAULT],
            [['phone'], 'required','on' => self::SCENARIO_UPDATE],
            [['email'], 'required','on' => self::SCENARIO_UPDATE_EMAIL],
            [['fio'], 'match', 'pattern'=> '/[А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+[ ][А-ЯЁа-яё]+/'],
            [['admin'], 'integer'],
            [['phone', 'email'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern'=> '/^\+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/'],
            [['password','token'], 'string', 'max' => 255],
            [['password'],'match', 'pattern' => '/^(?=.*[A-Z])(?=.*[\d])(?=.*[a-z])(?=.*[\W])[a-zA-Z\d\W]{8,20}$/'],
            [['phone','email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id пользователя',
            'fio' => 'ФИО',
            'email' => 'Емейл',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'admin' => 'Admin',
            'token' => 'токен',
        ];
    }
    public static function findIdentity($id)
    {
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
    return static::findOne(['token' => $token]);
    }
    public static function getByToken() {
        return self::findOne(['token' => str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'))]);
    }
    public function getId()
    {
    return $this->id_user;
    }
    public function getAuthKey()
    {
    }
    public function validateAuthKey($authKey)
    {
    }
    public function isAuthorized() {
        $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));
        if (!$token || $token != $this->token) {
            return false;
        }
        return true;
    }
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_EMAIL = 'update_email';
    const SCENARIO_DEFAULT = 'default';
    public function scenarios(){
    return [
        self::SCENARIO_UPDATE => ['phone'],
        self::SCENARIO_UPDATE_EMAIL => ['email'],
        self::SCENARIO_DEFAULT => ['fio','email','phone','password'],
    ];
    }
}