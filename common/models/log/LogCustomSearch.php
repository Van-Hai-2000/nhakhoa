<?php

namespace common\models\log;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ActiverecordlogSearch represents the model behind the search form about `common\models\log\Activerecordlog`.
 */
class LogCustomSearch extends LogCustom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idModel'], 'integer'],
            [['description', 'action', 'model', 'record_before', 'record_after', 'user_id'], 'safe'],
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
        $query = LogCustom::find();

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
            'idModel' => $this->idModel,
        ]);

        $query->joinWith(['userAdmin']);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'record_before', $this->record_before])
            ->andFilterWhere(['like', 'record_after', $this->record_after])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);
        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
