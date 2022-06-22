<?php

namespace common\models\medical_record;

use backend\models\UserAdmin;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\medical_record\Factory;

/**
 * FactorySearch represents the model behind the search form about `common\models\medical_record\Factory`.
 */
class FactorySearch extends Factory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'quantity', 'admin_id', 'time_return', 'created_at', 'updated_at', 'status'], 'integer'],
            [['money'], 'number'],
            [['insurance_code', 'insurance_code_private', 'branch_id', 'factory_id', 'device_id','user_id'], 'safe'],
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
        $query = Factory::find()->where(['status_delete' => 0]);

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
            'medical_record_id' => $this->medical_record_id,
            'factory.money' => $this->money,
            'quantity' => $this->quantity,
            'admin_id' => $this->admin_id,
            'time_return' => $this->time_return,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'factory.status' => $this->status,
        ]);

        $query->joinWith(['branch', 'userAdmin', 'user', 'loaimau']);

        $query->andFilterWhere(['like', 'insurance_code', $this->insurance_code])
            ->andFilterWhere(['like', 'insurance_code_private', $this->insurance_code_private]);
        $query->andFilterWhere(['like', 'branch.name', $this->branch_id]);
        $query->andFilterWhere(['like', 'user_admin.fullname', $this->factory_id]);
        $query->andFilterWhere(['like', 'user.username', $this->user_id]);
        $query->andFilterWhere(['like', 'loai_mau.name', $this->device_id]);
        $query->orderBy('created_at DESC');
        return $dataProvider;
    }

    public function searchFactory($params)
    {
        $query = Factory::find();
        $us = Yii::$app->user->getIdentity();
        if ($us->type == UserAdmin::USER_XUONG) {
            $query->where(['factory_id' => Yii::$app->user->id]);
        }


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
            'medical_record_id' => $this->medical_record_id,
            'factory.money' => $this->money,
            'quantity' => $this->quantity,
            'admin_id' => $this->admin_id,
            'time_return' => $this->time_return,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'factory.status' => $this->status,
        ]);

        $query->joinWith(['branch', 'userAdmin', 'user', 'loaimau']);

        $query->andFilterWhere(['like', 'insurance_code', $this->insurance_code])
            ->andFilterWhere(['like', 'insurance_code_private', $this->insurance_code_private]);

        $query->andFilterWhere(['like', 'branch.name', $this->branch_id]);
        $query->andFilterWhere(['like', 'userAdmin.fullname', $this->factory_id]);
        $query->andFilterWhere(['like', 'user.username', $this->user_id]);
        $query->andFilterWhere(['like', 'loai_mau.name', $this->device_id]);
        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
