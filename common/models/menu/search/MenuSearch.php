<?php

namespace common\models\menu\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\menu\Menu;

/**
 * MenuSearch represents the model behind the search form about `common\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'parent', 'linkto', 'order', 'status', 'target', 'created_at', 'updated_at'], 'integer'],
            [['name', 'alias', 'link', 'basepath', 'pathparams', 'values', 'icon_path', 'icon_name'], 'safe'],
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
        $query = Menu::find();

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
            'group_id' => $this->group_id,
            'parent' => $this->parent,
            'linkto' => $this->linkto,
            'order' => $this->order,
            'status' => $this->status,
            'target' => $this->target,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'basepath', $this->basepath])
            ->andFilterWhere(['like', 'pathparams', $this->pathparams])
            ->andFilterWhere(['like', 'values', $this->values])
            ->andFilterWhere(['like', 'icon_path', $this->icon_path])
            ->andFilterWhere(['like', 'icon_name', $this->icon_name]);

        return $dataProvider;
    }
}
