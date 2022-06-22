<?php

namespace common\models\medicine\search;

use common\models\medicine\Medicine;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\product\Product`.
 */
class MedicineSearch extends Medicine {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id','category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'category_track', 'code', 'avatar_path', 'avatar_name'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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

    public function search($params) {
        $query = Medicine::find();

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
            'price' => $this->price,
            'medicine.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'medicine.category_id' => $this->category_id,
        ]);
        $query->joinWith(['category']);

        $query->andFilterWhere(['like', 'medicine.name', $this->name])
                ->andFilterWhere(['like', 'code', $this->code]);

        if(!isset($_GET['sort'])) {
            $query->orderBy('updated_at DESC');
        }

        return $dataProvider;
    }

}
