<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\DiscountCode;

/**
 * DiscountCodeSearch represents the model behind the search form about `\common\models\product\DiscountCode`.
 */
class DiscountCodeSearch extends DiscountCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'time_start', 'time_end', 'type_discount', 'count', 'user_use', 'created_at', 'status'], 'integer'],
            [['value'], 'number'],
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
        $query = DiscountCode::find();

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
            'shop_id' => $this->shop_id,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'type_discount' => $this->type_discount,
            'value' => $this->value,
            'count' => $this->count,
            'user_use' => $this->user_use,
            'created_at' => $this->created_at,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
