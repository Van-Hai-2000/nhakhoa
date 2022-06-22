<?php

namespace common\models\recruitment\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\recruitment\Recruitment;

/**
 * RecruitmentSearch represents the model behind the search form about `common\models\Recruitment`.
 */
class RecruitmentSearch extends Recruitment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'level', 'quantity', 'priority', 'salaryrange', 'currency', 'salary_min', 'salary_max', 'experience', 'expiration_date', 'publicdate', 'created_at', 'updated_at', 'status', 'viewed'], 'integer'],
            [['title', 'alias', 'category_id', 'typeofworks', 'locations', 'skills'], 'safe'],
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
        $query = Recruitment::find();

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
            'level' => $this->level,
            'quantity' => $this->quantity,
            'priority' => $this->priority,
            'salaryrange' => $this->salaryrange,
            'currency' => $this->currency,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'experience' => $this->experience,
            'expiration_date' => $this->expiration_date,
            'publicdate' => $this->publicdate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'viewed' => $this->viewed,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'typeofworks', $this->typeofworks])
            ->andFilterWhere(['like', 'locations', $this->locations])
            ->andFilterWhere(['like', 'skills', $this->skills]);
        
        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
