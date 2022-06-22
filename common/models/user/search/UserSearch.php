<?php

namespace common\models\user\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

/**
 * UserSearch represents the model behind the search form about `common\models\user\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'sex', 'created_at', 'updated_at'], 'integer'],
            [['username', 'phone', 'address', 'avatar_path', 'avatar_name','admin_id'], 'safe'],
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
        $query = User::find();

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

        $query->joinWith(['userAdmin']);

        // grid filtering conditions
        $query->andFilterWhere([
            'user.id' => $this->id,
            'status' => $this->status,
            'sex' => $this->sex,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.phone', $this->phone])
            ->andFilterWhere(['like', 'user.address', $this->address])
            ->andFilterWhere(['like', 'user_admin.fullname', $this->admin_id])
            ->andFilterWhere(['like', 'avatar_path', $this->avatar_path])
            ->andFilterWhere(['like', 'avatar_name', $this->avatar_name]);

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
