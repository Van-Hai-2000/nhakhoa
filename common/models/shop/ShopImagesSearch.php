<?php

namespace common\models\shop;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\shop\ShopImages;

/**
 * ShopImagesSearch represents the model behind the search form about `common\models\shop\ShopImages`.
 */
class ShopImagesSearch extends ShopImages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['img_id', 'shop_id', 'site_id', 'user_id', 'height', 'width', 'created_time', 'modified_time', 'order', 'type'], 'integer'],
            [['name', 'path', 'display_name', 'description', 'alias', 'resizes'], 'safe'],
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
        $query = ShopImages::find();

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
            'img_id' => $this->img_id,
            'shop_id' => $this->shop_id,
            'site_id' => $this->site_id,
            'user_id' => $this->user_id,
            'height' => $this->height,
            'width' => $this->width,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'order' => $this->order,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'display_name', $this->display_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'resizes', $this->resizes]);

        return $dataProvider;
    }
}
