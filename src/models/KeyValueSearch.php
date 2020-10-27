<?php

namespace kvmanager\models;

use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

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
        $config = KeyValue::getAvailable();

        return [
            [[self::$namespaceFieldName], 'default', 'value' => array_key_first($config)],
            [[self::$groupFieldName], 'default', 'value' => current((array)current($config))],
            [[self::$namespaceFieldName, self::$groupFieldName], 'required'],
            [
                [self::$groupFieldName],
                'filter',
                'filter' => function ($gp) {
                    if (!self::permissionCheck($this->{self::$namespaceFieldName}, $gp)) {
                        throw new ForbiddenHttpException(' 权限错误');
                    }

                    return $gp;
                },
            ],
            [
                [
                    self::$keyFieldName,
                    'value',
                    'memo',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
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
        $query = KeyValue::find()
            ->with(['operator']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'attributes'   => ['id'],
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query
            ->andWhere([
                'namespace' => $this->namespace,
                'group'     => $this->group,
            ])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    }
}
