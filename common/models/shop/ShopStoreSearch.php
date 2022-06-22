<?php

namespace common\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\shop\ShopStore;

/**
 * ShopStoreSearch represents the model behind the search form about `common\models\shop\ShopStore`.
 */
class ShopStoreSearch extends ShopStore
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_time', 'modified_time', 'site_id', 'shop_id', 'group', 'order'], 'integer'],
            [['name', 'address', 'province_id', 'province_name', 'district_id', 'district_name', 'ward_id', 'ward_name', 'hotline', 'phone', 'email', 'hours', 'avatar_path', 'avatar_name', 'latlng', 'meta_title', 'meta_keywords', 'meta_description'], 'safe'],
            [['status'], 'boolean'],
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
        $query = ShopStore::find();

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
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'site_id' => $this->site_id,
            'shop_id' => $this->shop_id,
            'group' => $this->group,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'province_id', $this->province_id])
            ->andFilterWhere(['like', 'province_name', $this->province_name])
            ->andFilterWhere(['like', 'district_id', $this->district_id])
            ->andFilterWhere(['like', 'district_name', $this->district_name])
            ->andFilterWhere(['like', 'ward_id', $this->ward_id])
            ->andFilterWhere(['like', 'ward_name', $this->ward_name])
            ->andFilterWhere(['like', 'hotline', $this->hotline])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'hours', $this->hours])
            ->andFilterWhere(['like', 'avatar_path', $this->avatar_path])
            ->andFilterWhere(['like', 'avatar_name', $this->avatar_name])
            ->andFilterWhere(['like', 'latlng', $this->latlng])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        return $dataProvider;
    }
}
