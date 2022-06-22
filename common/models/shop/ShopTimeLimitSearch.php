<?php

namespace common\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\shop\ShopTimeLimit;

/**
 * ShopTimeLimitSearch represents the model behind the search form about `\common\models\shop\ShopTimeLimit`.
 */
class ShopTimeLimitSearch extends ShopTimeLimit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time', 'coin', 'created_at', 'updated_at', 'admin_id', 'status'], 'integer'],
            [['name'], 'safe'],
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
        $query = ShopTimeLimit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'time' => $this->time,
            'coin' => $this->coin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'admin_id' => $this->admin_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
