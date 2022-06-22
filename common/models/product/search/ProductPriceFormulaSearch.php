<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\ProductPriceFormula;

/**
 * ProductPriceFormulaSearch represents the model behind the search form about `common\models\product\ProductPriceFormula`.
 */
class ProductPriceFormulaSearch extends ProductPriceFormula
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code_app', 'name', 'formula_product', 'formula_gold', 'formula_fee', 'formula_stone', 'description'], 'safe'],
            [['coefficient1', 'coefficient2', 'coefficient3', 'coefficient4', 'coefficient5', 'coefficient6', 'coefficient7', 'coefficient8', 'coefficient9', 'coefficientm', 'coefficientx'], 'number'],
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
        $query = ProductPriceFormula::find();

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
            'status' => $this->status,
            'coefficient1' => $this->coefficient1,
            'coefficient2' => $this->coefficient2,
            'coefficient3' => $this->coefficient3,
            'coefficient4' => $this->coefficient4,
            'coefficient5' => $this->coefficient5,
            'coefficient6' => $this->coefficient6,
            'coefficient7' => $this->coefficient7,
            'coefficient8' => $this->coefficient8,
            'coefficient9' => $this->coefficient9,
            'coefficientm' => $this->coefficientm,
            'coefficientx' => $this->coefficientx,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code_app', $this->code_app])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'formula_product', $this->formula_product])
            ->andFilterWhere(['like', 'formula_gold', $this->formula_gold])
            ->andFilterWhere(['like', 'formula_fee', $this->formula_fee])
            ->andFilterWhere(['like', 'formula_stone', $this->formula_stone])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
