<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\InvalidArgumentException;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETED = 0;

    public $newPassword;
    public $newPasswordRepeat;

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'], // Требования к полям
            [['username'], 'string', 'max' => 255],
            [['email'], 'email'], // Валидация email

            [['newPassword', 'newPasswordRepeat'], 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * Метод для добавления меток атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',  // Атрибут username с меткой
            'email' => 'Электронная почта',   // Атрибут email с меткой
        ];
    }

    /**
     * Находит пользователя по его ID.
     * @param string|int $id
     * @return IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Находит пользователя по его аутентификационному ключу.
     * @param string $authKey
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * Находит пользователя по имени.
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Возвращает идентификатор пользователя.
     * @return int|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Возвращает аутентификационный ключ.
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Проверяет, совпадает ли аутентификационный ключ.
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Устанавливает пароль пользователя.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Проверяет пароль пользователя.
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генерирует новый аутентификационный ключ.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генерирует новый пароль.
     * @return string
     */
    public static function generatePassword()
    {
        return Yii::$app->security->generateRandomString(8);
    }

    public function beforeSave($insert)
    {
        if (!empty($this->newPassword)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->newPassword);
        }
        return parent::beforeSave($insert);
    }
}

