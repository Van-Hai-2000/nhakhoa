<?php

namespace common\models\user;

use backend\models\UserAdmin;
use common\components\ClaActiveRecordLog;
use common\models\auth\AuthAssignment;
use common\models\District;
use common\models\Province;
use common\models\Ward;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $phone
 * @property string $email
 * @property integer $status
 * @property string $address
 * @property integer $sex
 * @property integer $birthday
 * @property string $avatar_path
 * @property string $avatar_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $type_introduce
 * @property string $introduce_id
 * @property string $introduce
 * @property string $admin_id
 * @property string $province_id
 * @property string $district_id
 * @property string $ward_id
 * @property string $relationship
 */
class User extends ClaActiveRecordLog
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'username'], 'required'],
            [['phone'], 'unique'],
            [['status', 'created_at','sex', 'updated_at','user_id_app','introduce_id','introduce','type_introduce','admin_id','province_id','district_id','ward_id'], 'integer'],
            [['username', 'phone', 'address', 'avatar_path', 'avatar_name','username_app','email','src','relationship'], 'string', 'max' => 255],
            ['birthday','safe'],
            [['status', 'created_at','sex', 'updated_at','user_id_app','introduce_id','introduce','type_introduce','admin_id','birthday','province_id','district_id','ward_id'], 'filter', 'filter' => 'intval'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Mã bệnh nhân',
            'username' => 'Họ và tên',
            'relationship' => 'Mối quan hệ',
            'admin_id' => 'Người tạo',
            'src' => 'Ảnh đại diện',
            'phone' => 'Số điện thoại',
            'status' => 'Trạng thái',
            'address' => 'Địa chỉ',
            'sex' => 'Giới tính',
            'birthday' => 'Ngày sinh',
            'avatar_path' => 'Avatar Path',
            'avatar_name' => 'Avatar Name',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian cập nhật',
            'introduce' => 'Nguồn khác',
            'introduce_id' => 'Người giới thiệu',
            'type_introduce' => 'Nguồn giới thiệu',
            'province_id' => 'Tỉnh/ thành phố',
            'district_id' => 'Quận/ huyện',
            'ward_id' => 'Phường/ xã',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    static function getSex()
    {
        return [
            1 => 'Nam',
            2 => 'Nữ',
            3 => 'Không xác định'
        ];
    }

    static function getStatus()
    {
        return [
            1 => 'Kích hoạt',
            0 => 'Khóa',
        ];
    }

    static function getUser(){
        $users = self::find()->where(['status' => 1])->asArray()->all();
        $return = [];
        if($users){
            foreach ($users as $value){
                $return[$value['id']] = $value['phone'] . ' - ' .$value['username'];
            }
        }
        return $return;
    }

    static function getUserName(){
        $users = self::find()->where(['status' => 1])->asArray()->all();
        $return = [];
        if($users){
            foreach ($users as $value){
                $return[$value['username']] = $value['phone'] . ' - ' .$value['username'];
            }
        }
        return $return;
    }

    public function getUserAdmin(){
        return $this->hasOne(UserAdmin::className(),['id' => 'admin_id'])->select('fullname,id');
    }

    static function getTypeIntroduce()
    {
        return [
            1 => 'Người giới thiệu',
            2 => 'Nguồn khác',
        ];
    }

    function getDistrict($model)
    {
        $district = [];
        if ($model->province_id) {
            $district = District::dataFromProvinceId($model->province_id);
        }
        return $district;
    }

    function getWard($model)
    {
        $ward = [];
        if ($model->district_id) {
            $ward = Ward::dataFromDistrictId($model->district_id);
        }
        return $ward;
    }

    function getAuthAssignment()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }
    static  public function getAddress($pro,$dis,$ward){
        $province_id= Province::findOne($pro);
          $district_id = District::findOne($dis);
          $ward_id= Ward::findOne($ward);

          if(isset($province_id)&& $province_id && isset($district_id)&& $district_id &&  isset($ward_id)&& $ward_id ){
              return  $ward_id->name. " - " .  $district_id->name ." - "  .$province_id->name ;
          }
          else
              return '';
    }
}
