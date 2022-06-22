<?php

namespace common\models\sale\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sale\BranchSales;

/**
 * BranchSalesSearch represents the model behind the search form about `common\models\sale\BranchSales`.
 */
class BranchSalesSearch extends BranchSales
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'type_id', 'week', 'month', 'year', 'created_at', 'updated_at'], 'integer'],
            [['branch_name','branch_id'], 'safe'],
            [['money'], 'number'],
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
        $query = BranchSales::find();

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
        $query->joinWith(['branch']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'money' => $this->money,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'week' => $this->week,
            'month' => $this->month,
            'year' => $this->year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'branch.name', $this->branch_id])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name]);

        return $dataProvider;
    }
}
