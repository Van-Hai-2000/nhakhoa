<?php

namespace common\models\rating;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\rating\Rating;

/**
 * RatingSearch represents the model behind the search form about `common\models\rating\Rating`.
 */
class RatingSearch extends Rating
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'rating', 'type', 'object_id', 'status', 'order_item_id'], 'integer'],
            [['name', 'address', 'email', 'created_at', 'content'], 'safe'],
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
        $query = Rating::find();

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
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'type' => $this->type,
            'object_id' => $this->object_id,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'order_item_id' => $this->order_item_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'content', $this->content]);

        if(!isset($_GET['sort'])) {
             $query->orderBy('id DESC');
        }
        return $dataProvider;
    }
}
