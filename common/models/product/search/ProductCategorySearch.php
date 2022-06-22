<?php

namespace common\models\product\search;

use common\models\product\ProductCategory;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\product\Product`.
 */
class ProductCategorySearch extends ProductCategory {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id','category_id','status', 'order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'alias', 'avatar_path', 'avatar_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params) {
        $query = ProductCategory::find()->where('id > 0');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($_GET['sort'])) {
            $query->orderBy('updated_at DESC');
        }

        return $dataProvider;
    }

}
