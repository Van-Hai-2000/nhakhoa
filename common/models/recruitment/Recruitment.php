<?php

namespace common\models\recruitment;

use Yii;
use common\components\ClaLid;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use common\models\Province;
use common\models\recruitment\Category;
use common\models\recruitment\Skill;

/**
 * This is the model class for table "recruitment".
 *
 * @property string $id
 * @property string $user_id
 * @property string $title
 * @property string $alias
 * @property integer $level
 * @property string $category_id
 * @property string $typeofworks
 * @property string $locations
 * @property string $knowledge
 * @property string $skills
 * @property integer $quantity
 * @property integer $priority
 * @property integer $salaryrange
 * @property integer $currency
 * @property string $salary_min
 * @property string $salary_max
 * @property integer $experience
 * @property string $expiration_date
 * @property string $publicdate
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property string $viewed
 * @property string $avatar_path
 * @property string $avatar_name
 * @property integer $ishot
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_title
 */
class Recruitment extends \yii\db\ActiveRecord {

    const SALARY_DEAL = 1; // Lương thỏa thuận
    const SALARY_COMPETITION = 2; // Lương cạnh tranh
    const SALARY_DETAIL = 3; // Lương cụ thể

    public $avatar = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'recruitment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'category_id', 'typeofworks', 'locations', 'skills', 'quantity'], 'required'],
            [['user_id', 'level', 'quantity', 'knowledge', 'priority', 'salaryrange', 'currency', 'salary_min', 'salary_max', 'experience', 'expiration_date', 'publicdate', 'created_at', 'updated_at', 'status', 'viewed', 'ishot'], 'integer'],
            [['title', 'alias', 'avatar_path', 'avatar_name', 'meta_keywords', 'meta_description', 'meta_title'], 'string', 'max' => 255],
            [['avatar', 'typeofworks', 'category_id', 'locations', 'skills'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Tiêu đề',
            'alias' => 'Alias',
            'level' => 'Cấp bậc',
            'category_id' => 'Ngành nghề',
            'typeofworks' => 'Loại hình công việc',
            'locations' => 'Nơi làm việc',
            'knowledge' => 'Học vấn',
            'skills' => 'Kỹ năng',
            'quantity' => 'Số lượng cần tuyển',
            'priority' => 'Priority',
            'salaryrange' => 'Mức lương',
            'currency' => 'Đơn vị',
            'salary_min' => 'Mức lương tối thiểu',
            'salary_max' => 'Mức lương tối đa',
            'experience' => 'Kinh nghiệm',
            'expiration_date' => 'Hạn tuyển dụng',
            'publicdate' => 'Ngày đăng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'status' => 'Trạng thái',
            'viewed' => 'Lượt xem',
            'avatar' => 'Ảnh đại diện',
            'ishot' => 'Tin nổi bật',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'meta_title' => 'Meta Title',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->title);
            return true;
        } else {
            return false;
        }
    }

    public function getInfo() {
        return $this->hasOne(RecruitmentInfo::className(), ['recruitment_id', 'id']);
    }

    public static function arrayLevel() {
        return [
            '' => '',
            1 => 'Nhân viên',
            2 => 'Trưởng phòng',
            3 => 'Mới tốt nghiệp',
            4 => 'Chuyên gia',
            5 => 'Giám đốc và cấp cao hơn',
        ];
    }

    public static function getLevel($level) {
        $data = self::arrayLevel();
        return isset($data[$level]) ? $data[$level] : '';
    }

    public static function arrayTypeofwork() {
        return [
            1 => 'Toàn thời gian cố định',
            2 => 'Toàn thời gian tạm thời',
            3 => 'Bán thời gian cố định',
            4 => 'Bán thời gian tạm thời',
            5 => 'Theo hợp đồng tư vấn',
            6 => 'Thực tập',
            7 => 'Khác',
        ];
    }

    public static function arraySalaryDetail() {
        return [
            1 => '< 500',
            2 => '500 - 1000',
            3 => '1000 - 1500',
            4 => '1500 - 2000',
            5 => '2000 - 3000',
            6 => '> 3000',
        ];
    }

    public static function arraySalaryrange() {
        return [
            self::SALARY_DEAL => 'Thỏa thuận',
            self::SALARY_COMPETITION => 'Cạnh tranh',
            self::SALARY_DETAIL => 'Cụ thể',
        ];
    }

    public static function getSalaryDetail($item) {
        $data_temp = self::arraySalaryrange();
        if ($item['salaryrange'] == self::SALARY_DEAL || $item['salaryrange'] == self::SALARY_COMPETITION) {
            return $data_temp[$item['salaryrange']];
        } else if ($item['salaryrange'] == self::SALARY_DETAIL) {
            return '$' . $item['salary_min'] . ' - ' . '$' . $item['salary_max'];
        }
    }

    public static function arrayExperience() {
        return [
            1 => 'Dưới một năm',
            2 => '1 năm',
            3 => '2 năm',
            4 => '3 năm',
            5 => '4 năm',
            6 => '5 năm',
            7 => 'Trên 5 năm',
        ];
    }

    public static function arrayKnowledge() {
        return [
            0 => 'Không yêu cầu',
            1 => 'Trên đại học',
            2 => 'Đại học',
            3 => 'Cao đẳng',
            4 => 'Trung cấp',
            5 => 'Trung học',
        ];
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    public static function getRecruitment($options = []) {
        $condition = 't.status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        //
        if (isset($options['ishot']) && $options['ishot']) {
            $condition .= ' AND t.ishot=:ishot';
            $params[':ishot'] = ClaLid::STATUS_ACTIVED;
        }
        //
        if (isset($options['salary_min']) && $options['salary_min']) {
            $condition .= ' AND t.salaryrange=:salaryrange AND t.salary_min >= :salary_min';
            $params[':salaryrange'] = Recruitment::SALARY_DETAIL;
            $params[':salary_min'] = $options['salary_min'];
        }
        //
        if (isset($options['relation']) && $options['relation']) {
            $id = Yii::$app->request->get('id');
            $model = Recruitment::findOne($id);
            $condition .= ' AND t.id<>:id AND t.level=:level';
            $params[':id'] = $id;
            $params[':level'] = $model['level'];
        }
        //
        $limit = ClaLid::DEFAULT_LIMIT;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }
        //
        
        return (new Query())->select('t.*, r.username')
                ->from('recruitment t')
                ->leftJoin('user r', 'r.id = t.user_id')
                ->where($condition, $params)
                ->orderBy('t.id DESC')
                ->limit($limit)
                ->all();
    }

    public static function getAllRecruitment($options = []) {
        if (!$options['limit']) {
            $options['limit'] = ClaLid::DEFAULT_LIMIT;
        }
        if (!isset($options['page'])) {
            $options['page'] = 1;
        }
        $offset = ($options['page'] - 1) * $options['limit'];
        //
        $condition = 't.status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
        ];
        // search theo tỉnh/thành phố
        if (isset($options['location']) && $options['location']) {
            $condition .= " AND MATCH (t.locations) AGAINST (:location IN BOOLEAN MODE)";
            $params[':location'] = $options['location'];
        }
        // search theo ngành nghề
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (t.category_id) AGAINST (:category_id IN BOOLEAN MODE)";
            $params[':category_id'] = $options['category_id'];
        }
        // search theo keywords
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND MATCH (t.title) AGAINST (:title IN BOOLEAN MODE)";
            $params[':title'] = $options['keyword'];
        }
        return (new Query())->select('t.*, r.username')
                        ->from('recruitment t')
                        ->leftJoin('user r', 'r.id = t.user_id')
                        ->where($condition, $params)
                        ->orderBy('t.updated_at DESC')
                        ->limit($options['limit'])
                        ->offset($offset)
                        ->all();
    }

    public static function countAllRecruitment($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED,
        ];
        // search theo tỉnh/thành phố
        if (isset($options['location']) && $options['location']) {
            $condition .= " AND MATCH (locations) AGAINST (:location IN BOOLEAN MODE)";
            $params[':location'] = $options['location'];
        }
        // search theo ngành nghề
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (category_id) AGAINST (:category_id IN BOOLEAN MODE)";
            $params[':category_id'] = $options['category_id'];
        }
        // search theo keywords
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND MATCH (title) AGAINST (:title IN BOOLEAN MODE)";
            $params[':title'] = $options['keyword'];
        }
        return Recruitment::find()
                        ->where($condition, $params)
                        ->count();
    }

    /**
     * For Filter (tỉnh/thành phố) 
     * Có: count_job
     * @return type
     */
    public static function getLocationsSite($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        // search theo tỉnh/thành phố
        if (isset($options['location']) && $options['location']) {
            $condition .= " AND MATCH (locations) AGAINST (:location IN BOOLEAN MODE)";
            $params[':location'] = $options['location'];
        }
        // search theo ngành nghề
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (category_id) AGAINST (:category_id IN BOOLEAN MODE)";
            $params[':category_id'] = $options['category_id'];
        }
        // search theo keywords
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND MATCH (title) AGAINST (:title IN BOOLEAN MODE)";
            $params[':title'] = $options['keyword'];
        }
        //
        $provinces_temp = ArrayHelper::map(Province::find()->asArray()->all(), 'id', 'name');
        $locations = [];
        $provinces = (new Query())->select('locations')
                ->from('recruitment')
                ->where($condition, $params)
                ->column();
        if (isset($provinces) && $provinces) {
            foreach ($provinces as $province) {
                $province_explode = explode(' ', $province);
                foreach ($province_explode as $province_id) {
                    if (isset($locations[$province_id]['count_job'])) {
                        $locations[$province_id]['count_job'] ++;
                    } else {
                        $locations[$province_id]['count_job'] = 1;
                        $locations[$province_id]['province_name'] = $provinces_temp[$province_id];
                    }
                }
            }
        }
        return $locations;
    }

    /**
     * For SearchBox (tỉnh/thành phố) 
     * @return type
     */
    public static function getLocationsSiteForSearch() {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        //
        $provinces_temp = ArrayHelper::map(Province::find()->asArray()->all(), 'id', 'name');
        $locations = [];
        $provinces = (new Query())->select('locations')
                ->from('recruitment')
                ->where($condition, $params)
                ->column();
        if (isset($provinces) && $provinces) {
            foreach ($provinces as $province) {
                $province_explode = explode(' ', $province);
                foreach ($province_explode as $province_id) {
                    if (!isset($locations[$province_id])) {
                        $locations[$province_id] = $provinces_temp[$province_id];
                    }
                }
            }
        }
        return $locations;
    }

    /**
     * For Filter (ngành nghề)
     * Có: count_job
     * @return type
     */
    public static function getCategoriesSite($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        // search theo tỉnh/thành phố
        if (isset($options['location']) && $options['location']) {
            $condition .= " AND MATCH (locations) AGAINST (:location IN BOOLEAN MODE)";
            $params[':location'] = $options['location'];
        }
        // search theo ngành nghề
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (category_id) AGAINST (:category_id IN BOOLEAN MODE)";
            $params[':category_id'] = $options['category_id'];
        }
        // search theo keywords
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND MATCH (title) AGAINST (:title IN BOOLEAN MODE)";
            $params[':title'] = $options['keyword'];
        }
        $categories_temp = ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');
        $results = [];
        $categories = (new Query())->select('category_id')
                ->from('recruitment')
                ->where($condition, $params)
                ->column();
        if (isset($categories) && $categories) {
            foreach ($categories as $category) {
                $category_explode = explode(' ', $category);
                foreach ($category_explode as $category_id) {
                    if (isset($results[$category_id]['count_job'])) {
                        $results[$category_id]['count_job'] ++;
                    } else {
                        $results[$category_id]['count_job'] = 1;
                        $results[$category_id]['category_name'] = $categories_temp[$category_id];
                    }
                }
            }
        }
        return $results;
    }

    /**
     * For Filter (ngành nghề)
     * @return type
     */
    public static function getCategoriesSiteForSearch() {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        $categories_temp = ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');
        $results = [];
        $categories = (new Query())->select('category_id')
                ->from('recruitment')
                ->where($condition, $params)
                ->column();
        if (isset($categories) && $categories) {
            foreach ($categories as $category) {
                $category_explode = explode(' ', $category);
                foreach ($category_explode as $category_id) {
                    if (!isset($results[$category_id])) {
                        $results[$category_id] = $categories_temp[$category_id];
                    }
                }
            }
        }
        return $results;
    }

    public static function getSkillsSite($options = []) {
        $condition = 'status=:status';
        $params = [
            ':status' => ClaLid::STATUS_ACTIVED
        ];
        // search theo tỉnh/thành phố
        if (isset($options['location']) && $options['location']) {
            $condition .= " AND MATCH (locations) AGAINST (:location IN BOOLEAN MODE)";
            $params[':location'] = $options['location'];
        }
        // search theo ngành nghề
        if (isset($options['category_id']) && $options['category_id']) {
            $condition .= " AND MATCH (category_id) AGAINST (:category_id IN BOOLEAN MODE)";
            $params[':category_id'] = $options['category_id'];
        }
        // search theo keywords
        if (isset($options['keyword']) && $options['keyword']) {
            $condition .= " AND MATCH (title) AGAINST (:title IN BOOLEAN MODE)";
            $params[':title'] = $options['keyword'];
        }
        $skills_temp = ArrayHelper::map(Skill::find()->asArray()->all(), 'id', 'name');
        $results = [];
        $skills = (new Query())->select('skills')
                ->from('recruitment')
                ->where($condition, $params)
                ->column();
        if (isset($skills) && $skills) {
            foreach ($skills as $skill) {
                $skill_explode = explode(' ', $skill);
                foreach ($skill_explode as $skill_id) {
                    if (isset($results[$skill_id]['count_job'])) {
                        $results[$skill_id]['count_job'] ++;
                    } else {
                        $results[$skill_id]['count_job'] = 1;
                        $results[$skill_id]['skill_name'] = $skills_temp[$skill_id];
                    }
                }
            }
        }
        return $results;
    }

}
