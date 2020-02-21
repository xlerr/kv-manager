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
            [
                [
                    'key_value_key',
                    'key_value_value',
                    'key_value_memo',
                    'key_value_status',
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
            'sort'  => [
                'attributes'   => ['key_value_id'],
                'defaultOrder' => ['key_value_id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'key_value_key', $this->key_value_key])
            ->andFilterWhere(['like', 'key_value_value', $this->key_value_value])
            ->andFilterWhere(['like', 'key_value_memo', $this->key_value_memo])
            ->andFilterWhere(['key_value_status' => $this->key_value_status]);

        return $dataProvider;
    }
}
