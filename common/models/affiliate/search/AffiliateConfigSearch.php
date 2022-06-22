<?php

namespace common\models\affiliate\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\affiliate\AffiliateConfig;

/**
 * AffiliateConfigSearch represents the model behind the search form of `common\models\affiliate\AffiliateConfig`.
 */
class AffiliateConfigSearch extends AffiliateConfig
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cookie_expire', 'change_phone', 'min_price', 'status', 'created_time', 'modified_time'], 'integer'],
            [['commission_order', 'commission_click', 'commission_order_design', 'commission_click_design'], 'number'],
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
        $query = AffiliateConfig::find();

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
            'cookie_expire' => $this->cookie_expire,
            'commission_order' => $this->commission_order,
            'commission_click' => $this->commission_click,
            'commission_order_design' => $this->commission_order_design,
            'commission_click_design' => $this->commission_click_design,
            'change_phone' => $this->change_phone,
            'min_price' => $this->min_price,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
        ]);

        return $dataProvider;
    }
}
