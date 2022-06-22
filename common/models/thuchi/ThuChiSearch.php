<?php

namespace common\models\thuchi;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\thuchi\ThuChi;

/**
 * ThuChiSearch represents the model behind the search form about `common\models\thuchi\ThuChi`.
 */
class ThuChiSearch extends ThuChi
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'category_id','branch_id', 'nguoi_chi', 'created_at', 'updated_at','time_end','time_start','type_payment','medical_record_id'], 'integer'],
            [['name', 'note','admin_id','user_id'], 'safe'],
            [['money'], 'number'],
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
        $query = ThuChi::find()->where(['status_delete' => 0]);

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
        $query->joinWith(['userAdmin','category','branch','user','payment']);

        // grid filtering conditions
        $query->andFilterWhere([
            'thu_chi.id' => $this->id,
            'thu_chi.type' => $this->type,
            'category_id' => $this->category_id,
            'thu_chi.medical_record_id' => $this->medical_record_id,
            'money' => $this->money,
            'nguoi_chi' => $this->nguoi_chi,
            'thu_chi.branch_id' => $this->branch_id,
            'thu_chi.type_payment' => $this->type_payment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'thu_chi.name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'user.username', $this->user_id]);
        $query->andFilterWhere(['like', 'user_admin.fullname', $this->admin_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'thu_chi.time', $beginOfDay])
                ->andFilterWhere(['<', 'thu_chi.time', $endOfDay]);
        }

        $query->orderBy('time DESC');

        return $dataProvider;
    }
}
