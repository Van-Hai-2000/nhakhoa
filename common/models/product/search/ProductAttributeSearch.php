<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\ProductAttribute;

/**
 * ProductAttributeSearch represents the model behind the search form about `common\models\product\ProductAttribute`.
 */
class ProductAttributeSearch extends ProductAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'attribute_set_id', 'type_option', 'sort_order', 'is_configurable', 'is_filterable', 'is_system'], 'integer'],
            [['name', 'code', 'frontend_input', 'default_value'], 'safe'],
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
        $query = ProductAttribute::find();

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
            'attribute_set_id' => $this->attribute_set_id,
            'type_option' => $this->type_option,
            'sort_order' => $this->sort_order,
            'is_configurable' => $this->is_configurable,
            'is_filterable' => $this->is_filterable,
            'is_system' => $this->is_system,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'frontend_input', $this->frontend_input])
            ->andFilterWhere(['like', 'default_value', $this->default_value]);

        return $dataProvider;
    }
}
