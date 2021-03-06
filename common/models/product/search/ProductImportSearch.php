<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\ProductImport;

/**
 * ProductImportSearch represents the model behind the search form about `common\models\product\ProductImport`.
 */
class ProductImportSearch extends ProductImport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'quantity', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'color', 'size'], 'safe'],
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
        $query = ProductImport::find();

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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'size', $this->size]);
        
        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
