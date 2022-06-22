<?php

namespace common\models\affiliate\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\affiliate\Affiliate;

/**
 * AffiliateSearch represents the model behind the search form about `common\models\affiliate\Affiliate`.
 */
class AffiliateSearch extends Affiliate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'updated_at', 'user_update'], 'integer'],
            [['sale_before_product', 'sale_before_shop', 'sale_buy_shop_coin', 'sale_buy_shop_money', 'sale'], 'number'],
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
        $query = Affiliate::find();

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
            'sale_before_product' => $this->sale_before_product,
            'sale_before_shop' => $this->sale_before_shop,
            'sale_buy_shop_coin' => $this->sale_buy_shop_coin,
            'sale_buy_shop_money' => $this->sale_buy_shop_money,
            'sale' => $this->sale,
            'updated_at' => $this->updated_at,
            'user_update' => $this->user_update,
        ]);

        return $dataProvider;
    }
}
