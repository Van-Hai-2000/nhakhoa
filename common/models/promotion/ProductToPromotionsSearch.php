<?php

namespace common\models\promotion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\promotion\ProductToPromotions;

/**
 * ProductToPromotionsSearch represents the model behind the search form about `common\model\promotion\ProductToPromotions`.
 */
class ProductToPromotionsSearch extends ProductToPromotions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'promotion_id', 'product_id', 'created_time'], 'integer'],
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
        $query = ProductToPromotions::find();

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
            'promotion_id' => $params['ProductToPromotions']['promotion_id'],
            'product_id' => $this->product_id,
            'created_time' => $this->created_time,
        ]);

        return $dataProvider;
    }
}
