<?php

namespace kvmanager\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KeyValueSearch represents the model behind the search form about `KeyValue`.
 */
class KeyValueSearch extends KeyValue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_value_id'], 'integer'],
            [
                [
                    'key_value_key',
                    'key_value_value',
                    'key_value_memo',
                    'key_value_status',
                    'key_value_create_at',
                    'key_value_update_at',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
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
        $query = KeyValue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => 1,
//            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['key_value_id' => $this->key_value_id])
            ->andFilterWhere(['like', 'key_value_key', $this->key_value_key])
            ->andFilterWhere(['like', 'key_value_value', $this->key_value_value])
            ->andFilterWhere(['like', 'key_value_memo', $this->key_value_memo])
            ->andFilterWhere(['like', 'key_value_status', $this->key_value_status]);

        return $dataProvider;
    }
}
