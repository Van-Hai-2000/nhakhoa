<?php

namespace common\models\menu;

use Yii;
use yii\db\Query;
use common\models\news\NewsCategory;
use common\models\news\ContentPage;
use common\models\product\ProductCategory;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $group_id
 * @property string $name
 * @property string $name_en
 * @property string $alias
 * @property string $description
 * @property string $description_en
 * @property string $parent
 * @property integer $linkto
 * @property string $link
 * @property string $basepath
 * @property string $pathparams
 * @property string $order
 * @property integer $status
 * @property integer $target
 * @property string $values
 * @property string $icon_path
 * @property string $icon_name
 * @property string $created_at
 * @property string $updated_at
 */
class Menu extends \yii\db\ActiveRecord {

    const LINKTO_INNER = 1; // link thuộc website
    const LINKTO_OUTER = 0; // link ngoài website
    const TARGET_BLANK = 1; // tab mới
    const TARGET_UNBLANK = 0; // tab hiện tại
    const ROOT_CATEGORY = 0;

    private $_menus = array(self::ROOT_CATEGORY => ' --- Không chọn --- ');
    private $_dataProvider = array();
    private $_menus_widget = array();

    const MENUTYPE_NEWS_CATEGORY = 1; // News category
    const MENUTYPE_CONTENT_PAGE = 2; // Content page
    const MENUTYPE_NORMAL = 3; // Link thông thường
    const MENUTYPE_PRODUCT_CATEGORY = 4; // Product category
    //
    const MENU_NONE = 0; // javascript:void(0)
    const MENU_HOME = 1; // Trang chủ
    const MENU_CONTACT = 2; // Liên hệ
    const MENU_ABOUT = 3; // Giới thiêu
    const MENU_VIDEO = 4; // Video
    const MENU_AQ = 5; // Câu hỏi thường gặp
    const MENU_GOLD = 6; // Câu hỏi thường gặp
    
    public $avatar = '';

    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['group_id', 'name', 'parent'], 'required'],
            [['group_id', 'parent', 'linkto', 'order', 'status', 'target', 'created_at', 'updated_at'], 'integer'],
            [['name', 'name_en' , 'alias', 'basepath', 'pathparams', 'values', 'icon_path', 'icon_name', 'avatar_path', 'avatar_name'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 500],
            [['avatar', 'description', 'description_en'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'group_id' => 'Nhóm menu',
            'name' => 'Tên menu',
            'name_en' => 'Tên menu tiếng anh',
            'description' => 'Mô tả',
            'description_en' => 'Mô tả tiếng anh',
            'alias' => 'Alias',
            'parent' => 'Menu cha',
            'linkto' => 'Loại menu',
            'link' => 'Trang đích',
            'basepath' => 'Basepath',
            'pathparams' => 'Pathparams',
            'order' => 'Thứ tự',
            'status' => 'Trạng thái',
            'target' => 'Mở menu',
            'values' => 'Values',
            'icon_path' => 'Icon Path',
            'icon_name' => 'Icon Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            $this->alias = \common\components\HtmlFormat::parseToAlias($this->name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * return data for menu widget
     * @param type $parent
     * @param type $group_id
     * @return type
     */
    public function dataMenuWidget($parent = 0, $group_id = 0, &$menus) {
        $data = Menu::find()->where(['parent' => $parent, 'group_id' => $group_id])->all();
        if ($data) {
            foreach ($data as $menu) {
                $menus[$menu->id] = $menu->attributes;
                $menus[$menu->id]['items'] = $this->dataMenuWidget($menu->id, $group_id, $menus);
            }
        }
        return $menus;
    }

    /**
     * return options menu
     * @param type $parent
     * @param type $level
     * @return type
     */
    public function optionsMenu($parent = 0, $level = 0) {
        $data = Menu::find()->where(['parent' => $parent, 'group_id' => $this->group_id])->all();
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $menu) {
                $this->_menus[$menu->id] = $glue . $menu->name;
                $this->optionsMenu($menu->id, $level);
            }
        }

        return $this->_menus;
    }

    /**
     * Get data provider type array for gridview
     * @param type $parent
     * @param type $level
     * @param type $group_id
     * @return type
     */
    public function getDataProvider($parent = 0, $level = 0, $group_id) {
        $data = Menu::find()->where(['parent' => $parent, 'group_id' => $group_id])->all();
        $glue = str_repeat('- - - ', $level);
        if ($data) {
            $level++;
            foreach ($data as $menu) {
                $menu->name = $glue . $menu->name;
                $this->_dataProvider[$menu->id] = $menu->attributes;
                $this->getDataProvider($menu->id, $level, $menu->group_id);
            }
        }

        return $this->_dataProvider;
    }

    /**
     * Get link inner
     * @return type
     */
    public static function getInnerLinks() {
        // Link thông thường
        $array['Link thông thường'] = self::getNormalLink();
        // Danh mục sản phẩm
        $array['Danh mục sản phẩm'] = self::getProductCategoryLink();
        // Danh mục tin tức
        $array['Danh mục tin tức'] = self::getNewsCategoryLink();
        // Trang nội dung
        $array['Trang nội dung'] = self::getContentPageLink();
        return $array;
    }

    /**
     * Link thông thường
     * @return array
     */
    public static function getNormalLink() {
        $results = [
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_NONE]) => '--- Chọn trang đích ---',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_HOME]) => 'Trang chủ',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_CONTACT]) => 'Trang liên hệ',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_ABOUT]) => 'Trang giới thiệu',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_VIDEO]) => 'Trang video',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_GOLD]) => 'Trang giá vàng',
            json_encode(['t' => self::MENUTYPE_NORMAL, 'oi' => self::MENU_AQ]) => 'Trang Câu hỏi thường gặp'
        ];
        return $results;
    }

    /**
     * get Info of normal link
     * @param type $linkinfo
     * @return string|boolean
     */
    public static function getMenuLinkNormal($linkinfo = []) {
        if (!isset($linkinfo['oi'])) {
            return false;
        }
        $return = array();
        //
        switch ($linkinfo['oi']) {
            case self::MENU_NONE: {
                    $return = [
                        'basepath' => '',
                        'pathparams' => '',
                    ];
                }
                break;
            case self::MENU_HOME: {
                    $return = [
                        'basepath' => '',
                        'pathparams' => json_encode([]),
                    ];
                }
                break;
            case self::MENU_CONTACT: {
                    $return = [
                        'basepath' => '/site/contact',
                        'pathparams' => json_encode([]),
                    ];
                }
                break;
            case self::MENU_ABOUT: {
                    $return = [
                        'basepath' => '/site/about',
                        'pathparams' => json_encode([]),
                    ];
                }
                break;
            case self::MENU_VIDEO: {
                    $return = [
                        'basepath' => '/media/video/index',
                        'pathparams' => json_encode([]),
                    ];
                }
                break;
            case self::MENU_AQ: {
                    $return = [
                        'basepath' => '/site/question-answer',
                        'pathparams' => json_encode([]),
                    ];
                }
            case self::MENU_GOLD: {
                    $return = [
                        'basepath' => '/site/gold',
                        'pathparams' => json_encode([]),
                    ];
                }
                break;
        }
        //
        return $return;
    }

    /**
     * Link trang nội dung
     * @return type
     */
    public static function getContentPageLink() {
        $results = array();
        //
        $data = (new Query())->select('id, title')
                ->from('content_page')
                ->orderBy('id DESC')
                ->all();
        //
        if (isset($data) && $data) {
            foreach ($data as $page) {
                $results[json_encode(array('t' => self::MENUTYPE_CONTENT_PAGE, 'oi' => (int) $page['id']))] = $page['title'];
            }
        }
        //
        return $results;
    }
    
    /**
     * Link danh mục sản phẩm
     * @return type
     */
    public static function getProductCategoryLink() {
        $results = array();
        //
        $model = new ProductCategory();
        $categories = $model->optionsCategory();
        unset($categories['']);
        //
        foreach ($categories as $cat_id => $cat_name) {
            $results[json_encode(array('t' => self::MENUTYPE_PRODUCT_CATEGORY, 'oi' => (int) $cat_id))] = $cat_name;
        }
        //
        return $results;
    }

    /**
     * Link danh mục tin tức
     * @return type
     */
    public static function getNewsCategoryLink() {
        $results = array();
        //
        $model = new NewsCategory();
        $categories = $model->optionsCategory();
        unset($categories['']);
        //
        foreach ($categories as $cat_id => $cat_name) {
            $results[json_encode(array('t' => self::MENUTYPE_NEWS_CATEGORY, 'oi' => (int) $cat_id))] = $cat_name;
        }
        //
        return $results;
    }

    /**
     * Get info of link
     * @param type $linkinfo
     * @return boolean
     */
    public static function getMenuLinkInfo($linkinfo = array()) {
        if (!isset($linkinfo['t'])) {
            return false;
        }
        $return = array();
        switch ($linkinfo['t']) {
            case self::MENUTYPE_NORMAL: {
                    $return = self::getMenuLinkNormal($linkinfo);
                }
                break;
            case self::MENUTYPE_NEWS_CATEGORY: {
                    $return = self::getMenuLinkNewsCategory($linkinfo);
                }
                break;
            case self::MENUTYPE_PRODUCT_CATEGORY: {
                    $return = self::getMenuLinkProductCategory($linkinfo);
                }
                break;
            case self::MENUTYPE_CONTENT_PAGE: {
                    $return = self::getMenuLinkContentPage($linkinfo);
                }
                break;
        }
        return $return;
    }

    /**
     * get link news category
     * @param type $linkinfo
     * @return boolean|string
     */
    public static function getMenuLinkContentPage($linkinfo = array()) {
        $oid = (int) $linkinfo['oi'];
        if (!$oid) {
            return false;
        }
        $return = array();
        $page = ContentPage::findOne($oid);
        if ($page !== NULL) {
            $return = [
                'basepath' => '/content-page/detail',
                'pathparams' => json_encode(['id' => $oid, 'alias' => $page->alias]),
            ];
        }
        return $return;
    }

    /**
     * get link news category
     * @param type $linkinfo
     * @return boolean|string
     */
    public static function getMenuLinkNewsCategory($linkinfo = array()) {
        $oid = (int) $linkinfo['oi'];
        if (!$oid) {
            return false;
        }
        $return = array();
        $category = NewsCategory::findOne($oid);
        if ($category !== NULL) {
            $return = [
                'basepath' => '/news/news/category',
                'pathparams' => json_encode(['id' => $oid, 'alias' => $category->alias]),
            ];
        }
        return $return;
    }
    
    /**
     * get link news category
     * @param type $linkinfo
     * @return boolean|string
     */
    public static function getMenuLinkProductCategory($linkinfo = array()) {
        $oid = (int) $linkinfo['oi'];
        if (!$oid) {
            return false;
        }
        $return = array();
        $category = ProductCategory::findOne($oid);
        if ($category !== NULL) {
            $return = [
                'basepath' => '/product/product/category',
                'pathparams' => json_encode(['id' => $oid, 'alias' => $category->alias]),
            ];
        }
        return $return;
    }

    /**
     * check url is active or not
     * @param type $url
     */
    public static function checkActive($url, $options = array()) {
        $currenturl = '';
        if ($options['currenturl']) {
            $currenturl = $options['currenturl'];
        } else {
            $currenturl = Yii::$app->request->getUrl();
        }
        return (str_replace('/', '', $url) == str_replace('/', '', $currenturl)) ? true : false;
    }

}
