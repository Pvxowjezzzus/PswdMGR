<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Passwords;

/**
 * PasswordsSearch represents the model behind the search form of `app\models\Passwords`.
 */
class PasswordsSearch extends Passwords
{
    public $roles; // Виртуальное поле для поиска и сортировки по ролям

    public function rules()
    {
        return [
            [['id', 'id_service', 'id_organization'], 'integer'],
            [['hash', 'roles', 'link'], 'safe'], // Разрешаем фильтрацию и сортировку по ролям
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Passwords::find()
            ->select(['passwords.*', 'COALESCE(GROUP_CONCAT(password_role.role_name SEPARATOR ", "), "") AS roles'])
            ->leftJoin('password_role', 'password_role.password_id = passwords.id')
            ->groupBy('passwords.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'id_service',
                    'id_organization',
                    'hash',
                    'roles' => [
                        'asc' => ['roles' => SORT_ASC],
                        'desc' => ['roles' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 'link', $this->link]); // Поиск по ссылке

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Фильтрация по ролям
        if (!empty($this->roles)) {
            $query->andHaving(['like', 'roles', $this->roles]);
        }

        return $dataProvider;
    }

}
