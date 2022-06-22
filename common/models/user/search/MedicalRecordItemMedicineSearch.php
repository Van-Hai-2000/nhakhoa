<?php

namespace common\models\user\search;

use common\models\user\MedicalRecordItemMedicine;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MedicalRecordItemMedicineSearch represents the model behind the search form about `common\models\user\MedicalRecordItemChild`.
 */
class MedicalRecordItemMedicineSearch extends MedicalRecordItemMedicine
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'medical_record_id', 'medical_record_item_id','quantity','branh_id','created_at', 'updated_at','time_end','time_start'], 'integer'],
            [['product_name', 'doctor_id', 'medicine_id'], 'safe'],
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
        $query = MedicalRecordItemMedicine::find();

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

        $query->joinWith(['user','medicine','userAdmin','branch']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'medical_record_id' => $this->medical_record_id,
            'medical_record_item_id' => $this->medical_record_item_id,
            'quantity' => $this->quantity,
            'medical_record_item_medicine.branh_id' => $this->branh_id,
            'money' => $this->money,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->user_id])
            ->andFilterWhere(['like', 'medicine.name', $this->medicine_id])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'user_admin.fullname', $this->doctor_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_item_medicine.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_item_medicine.created_at', $endOfDay]);
        }


        return $dataProvider;
    }
}
