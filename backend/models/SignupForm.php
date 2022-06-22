<?php

namespace backend\models;

use common\components\UploadLib;
use common\models\banner\Banner;
use yii\base\Model;
use backend\models\UserAdmin;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $password2;
    public $status;
    public $type;
    public $branch_id;
    public $vai_tro;
    public $fullname;
    public $phone;
    public $src;
    public $phone2;
    public $identification;
    public $image_identification_before;
    public $image_identification_after;
    public $date_range_identification;
    public $issued_by_identification;
    public $specialize;
    public $degree;
    public $name_training_unit;
    public $graduation_year;
    public $specialist;
    public $number_of_certificates;
    public $date_range_certificates;
    public $issued_by_certificates;
    public $work_experience;
    public $contract_status;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\UserAdmin', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['identification' ,'trim'],
            ['identification' ,'required'],
            ['identification', 'unique', 'targetClass' => '\backend\models\UserAdmin', 'message' => 'CCCD/CMND Không được trùng nhau'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\UserAdmin', 'message' => 'This email address has already been taken.'],
            ['password', 'required', 'on' => 'create'],
            ['password2', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],
            ['password2', 'string', 'min' => 6],
            [['status','branch_id','vai_tro'], 'integer'],
            ['type', 'integer'],
            [['fullname','src','image_identification_before','image_identification_after','issued_by_identification','name_training_unit','graduation_year','specialist','issued_by_certificates','work_experience'], 'string', 'max' => 255],
            [['phone','phone2'], 'string','min'=>10 , 'max' => 10],
            [['identification'],'integer','min'=>9],
            [['date_range_identification','specialize','degree','number_of_certificates','date_range_certificates'],'integer'],
            [['contract_status'] ,'integer']
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'password2' => 'Mật khẩu cấp 2',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'type' => 'Loại tài khoản',
            'branch_id' => 'Chi nhánh',
            'vai_tro' => 'Loại tài khoản',
            'fullname' => 'Họ và tên',
            'src' => 'Ảnh đại diện',
            'phone' => 'Số điện thoại',
            'phone2' => 'Số điện thoại 2',
            'identification'=> 'Số CMND/CCCD',
            'date_range_identification' => 'Ngày cấp',
            'issued_by_identification' => 'Nơi cấp',
            'image_identification_before' => 'Ảnh CMND/CCCD mặt trước',
            'image_identification_after' => 'Ảnh CMND/CCCD mặt sau',
            'specialize' => 'Chuyên môn',
            'degree' => 'Bằng cấp',
            'name_training_unit'=> 'Đơn vị đào tạo',
            'graduation_year' => 'Năm tốt nghiệp',
            'specialist' => 'Chuyên khoa',
            'number_of_certificates' => 'Số chứng chỉ hành nghề',
            'date_range_certificates' => 'Ngày cấp chứng chỉ',
            'issued_by_certificates'=> 'Nơi cấp chứng chỉ',
            'work_experience' => 'Kinh nghiệm làm việc',
            'contract_status' => 'Tình trạng hợp đồng'
        ];
    }

    /**
     * Signs user up.
     *
     * @return UserAdmin|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new UserAdmin();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->type = $this->type;
        $user->branch_id = $this->branch_id;
        $user->vai_tro = $this->vai_tro;
        $user->fullname = $this->fullname;
        $user->phone = $this->phone;
        $user->src = $this->src;
        $user->phone2 = $this->phone2;
        $user->identification = $this->identification;
        $user->date_range_identification = $this->date_range_identification;
        $user->issued_by_identification = $this->issued_by_identification;
        $user->image_identification_before =$this->image_identification_before;
        $user->image_identification_after = $this->image_identification_after;
        $user->specialize = $this->specialize;
        $user->degree=$this->degree;
        $user->name_training_unit = $this->name_training_unit;
        $user->graduation_year = $this->graduation_year;
        $user->specialist = $this->specialist;
        $user->number_of_certificates  = $this->number_of_certificates;
        $user->date_range_certificates = $this->date_range_certificates;
        $user->issued_by_certificates = $this->issued_by_certificates;
        $user->work_experience= $this->work_experience;
        $user->contract_status = $this->contract_status;
        $user->setPassword($this->password);
        $user->setPassword2($this->password2);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
