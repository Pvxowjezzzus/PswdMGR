<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property string $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property int|null $data
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AuthItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item'; // Это имя таблицы в базе данных
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['rule_name'], 'string', 'max' => 64],
            [['data'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'type' => 'Type',
            'description' => 'Описание',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
