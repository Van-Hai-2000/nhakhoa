<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\ProductCurrency;

/**
 * ProductCurrencySearch represents the model behind the search form about `common\models\product\ProductCurrency`.
 */
class ProductCurrencySearch extends ProductCurrency
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gold_yn', 'money_yn', 'created_at', 'updated_at'], 'integer'],
            [['code_app', 'name'], 'safe'],
            [['price_sell', 'price_buy'], 'number'],
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
        $query = ProductCurrency::find();

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
            'price_sell' => $this->price_sell,
            'price_buy' => $this->price_buy,
            'gold_yn' => $this->gold_yn,
            'money_yn' => $this->money_yn,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code_app', $this->code_app])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
