<?php

namespace common\models\product\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\product\Product`.
 */
class ProductSearch extends Product {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'brand', 'category_id', 'quantity', 'status', 'ishot', 'viewed','order', 'created_at', 'updated_at', 'voso_connect'], 'integer'],
            [['name', 'alias', 'category_track', 'code', 'barcode', 'avatar_path', 'avatar_name', 'meta_title', 'meta_description', 'meta_keywords'], 'safe'],
            [['price', 'price_market'], 'number'],
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
    public function searchShop($shop_id, $params) {
        $query = Product::find()->andFilterWhere(['shop_id' => $shop_id]);

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
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'price_market' => $this->price_market,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'ishot' => $this->ishot,
            'voso_connect' => $this->voso_connect,
            'viewed' => $this->viewed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'category_track', $this->category_track])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'barcode', $this->barcode])
                ->andFilterWhere(['like', 'avatar_path', $this->avatar_path])
                ->andFilterWhere(['like', 'avatar_name', $this->avatar_name])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords]);


        return $dataProvider;
    }

    public function search($params) {
        $query = Product::find()->where('id > 0');

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
            'brand' => $this->brand,
            // 'category_id' => \common\models\product\ProductCategory::getIdChildAll($this->category_id),
            'price' => $this->price,
            'price_market' => $this->price_market,
            'category_id' => $this->category_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'ishot' => $this->ishot,
            'voso_connect' => $this->voso_connect,
            'viewed' => $this->viewed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'category_track', $this->category_track])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'barcode', $this->barcode])
                ->andFilterWhere(['like', 'avatar_path', $this->avatar_path])
                ->andFilterWhere(['like', 'avatar_name', $this->avatar_name])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords]);

        if(!isset($_GET['sort'])) {
            $query->orderBy('updated_at DESC');
        }

        return $dataProvider;
    }

}
