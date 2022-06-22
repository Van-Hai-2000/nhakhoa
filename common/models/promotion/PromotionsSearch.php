<?php

namespace common\models\promotion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\promotion\Promotions;

/**
 * PromotionsSearch represents the model behind the search form about `common\model\promotion\Promotions`.
 */
class PromotionsSearch extends Promotions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'startdate', 'enddate', 'created_time', 'category_id', 'order'], 'integer'],
            [['name', 'sortdesc', 'description', 'alias', 'meta_title', 'meta_keywords', 'meta_description', 'image_path', 'image_name'], 'safe'],
            [['showinhome', 'ishot'], 'boolean'],
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
        $query = Promotions::find();

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
            'startdate' => $this->startdate,
            'enddate' => $this->enddate,
            'showinhome' => $this->showinhome,
            'created_time' => $this->created_time,
            'ishot' => $this->ishot,
            'category_id' => $this->category_id,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sortdesc', $this->sortdesc])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'image_path', $this->image_path])
            ->andFilterWhere(['like', 'image_name', $this->image_name]);

        return $dataProvider;
    }
}
