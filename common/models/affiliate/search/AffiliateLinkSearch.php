<?php

namespace common\models\affiliate\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\affiliate\AffiliateLink;

/**
 * AffiliateLinkSearch represents the model behind the search form about `common\models\affiliate\AffiliateLink`.
 */
class AffiliateLinkSearch extends AffiliateLink
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'object_id', 'updated_at', 'created_at'], 'integer'],
            [['url', 'link', 'link_short', 'campaign_source', 'aff_type', 'campaign_name', 'campaign_content'], 'safe'],
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
        $query = AffiliateLink::find();

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
            'type' => $this->type,
            'object_id' => $this->object_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'link_short', $this->link_short])
            ->andFilterWhere(['like', 'campaign_source', $this->campaign_source])
            ->andFilterWhere(['like', 'aff_type', $this->aff_type])
            ->andFilterWhere(['like', 'campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'campaign_content', $this->campaign_content]);

        return $dataProvider;
    }
}
