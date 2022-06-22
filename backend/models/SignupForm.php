<?php

namespace backend\models;

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
            [['fullname','src'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
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

        $user->setPassword($this->password);
        $user->setPassword2($this->password2);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
