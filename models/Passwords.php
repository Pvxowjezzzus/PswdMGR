<?php

namespace app\models;

use Yii;
use yii\base\Security;

/**
 * This is the model class for table "passwords".
 *
 * @property int $id
 * @property int $id_service
 * @property int $id_organization
 * @property string $password
 * @property string $hash
 *
 * @property Organizations $organization
 * @property Services $service
 */
class Passwords extends \yii\db\ActiveRecord
{
    public $roles;
    public $link;
    public $plain_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passwords';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_service', 'id_organization'], 'required'],
            [['id_service', 'id_organization'], 'integer'],
            [['hash', 'comment'], 'string'],
            [['link'], 'safe'],
            [['plain_password'], 'string', 'min' => 6], // Минимальная длина
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organizations::class, 'targetAttribute' => ['id_organization' => 'id']],
            [['id_service'], 'exist', 'skipOnError' => true, 'targetClass' => Services::class, 'targetAttribute' => ['id_service' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_service' => 'Сервис',
            'id_organization' => 'Организация',
            'hash' => 'Hash',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organizations::class, ['id' => 'id_organization']);
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::class, ['id' => 'id_service']);
    }

    public function getRoles()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'role_name'])
            ->viaTable('password_role', ['password_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        // Устанавливаем значение виртуального атрибута
        $this->link = "https://passmgr.klas.pro/hash/" . urlencode($this->hash);
    }

    public static function encryptPassword($password)
    {
        $key = getenv('FLASH_KEY');
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedPassword = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);

        // Кодируем IV + пароль в Base64, убираем `=`, делаем URL-friendly
        return rtrim(strtr(base64_encode($iv . $encryptedPassword), '+/', '-_'), '=');
    }

    public static function decryptPassword($encrypted)
    {
        $key = getenv('FLASH_KEY');

        // Делаем обратное преобразование, восстанавливаем `=`
        $encrypted = strtr($encrypted, '-_', '+/');
        $encrypted = str_pad($encrypted, strlen($encrypted) % 4 ? strlen($encrypted) + 4 - strlen($encrypted) % 4 : strlen($encrypted), '=', STR_PAD_RIGHT);

        $decoded = base64_decode($encrypted);

        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decoded, 0, $ivLength);
        $encryptedPassword = substr($decoded, $ivLength);

        return openssl_decrypt($encryptedPassword, 'aes-256-cbc', $key, 0, $iv);
    }
}
