<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\MapPriceFormula;

/**
 * MapPriceFormulaSearch represents the model behind the search form about `common\models\product\MapPriceFormula`.
 */
class MapPriceFormulaSearch extends MapPriceFormula
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code_app', 'name', 'price_formula'], 'safe'],
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
        $query = MapPriceFormula::find();

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
        ]);

        $query->andFilterWhere(['like', 'code_app', $this->code_app])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'price_formula', $this->price_formula]);

        return $dataProvider;
    }
}
