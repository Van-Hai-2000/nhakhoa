<?php

namespace common\models\qa\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\qa\QA;

/**
 * NewsSearch represents the model behind the search form about `common\models\News`.
 */
class QASearch extends QA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status', 'updated_at', 'publicdate', 'viewed', 'ishot', 'created_at'], 'integer'],
            [['title', 'alias', 'short_description', 'description', 'meta_keywords', 'meta_description', 'meta_title', 'avatar_path', 'avatar_name', 'author', 'source'], 'safe'],
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
        $query = QA::find();

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
            'category_id' => $this->category_id,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'publicdate' => $this->publicdate,
            'viewed' => $this->viewed,
            'ishot' => $this->ishot,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'avatar_path', $this->avatar_path])
            ->andFilterWhere(['like', 'avatar_name', $this->avatar_name])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'source', $this->source]);
        
        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
