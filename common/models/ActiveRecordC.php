<?php

namespace common\models;

use ReflectionClass;
use Yii;
use yii\helpers\Inflector;

class ActiveRecordC extends \yii\db\ActiveRecord
{

    const DEFAULT_ORDER = 'id DESC';
    const DEFAULT_LIMIT = 12;
    public $default_order = 'id DESC';
    public $default_limit = 12;
    public $promt = '---Chọn---';
    public $_view_form = false;
    public $keyword = false;

    public $type_search = 0;
    public $link_detail = '';
    public $cols_search = [
        'id' => 'id',
        'alias' => 'alias',
        'title' => 'title',
        'meta_keywords' => 'meta_keywords',
        'short_description' => 'short_description',
        'avatar_name' => 'avatar_name',
        'avatar_path' => 'avatar_path',
    ];

    public static function changeKey($string)
    {
        return $string = \common\components\HtmlFormat::parseToAlias($string);
    }

    public function getByAttr($options = [])
    {
        $table = $this->getTableName();
        $query = (new \yii\db\Query())->select('*')->from($table);
        $query = $this->beforeAttr($query, $options);
        if (isset($options['attr']) && $options['attr']) {
            if (isset($options['attr']['keyword']) && $options['attr']['keyword']) {
                $keyword = \common\components\HtmlFormat::parseToAlias($options['attr']['keyword']);
                $stri = '1=0';
                foreach ($this->attributes as $key => $value) {
                    $stri .= " OR $table.$key LIKE '%$keyword%'";
                }
                $query->andWhere($stri);
                unset($options['attr']['keyword']);
            }
            foreach ($options['attr'] as $key => $value) {
                if (array_key_exists($key, $this->attributes) && ($value || $value === 0)) {
                    if (is_array($value) || is_numeric($value)) {
                        $query->andWhere([$table . '.' . $key => $value]);
                    } else {
                        $query->andWhere(['like', $table . '.' . $key, $value]);
                    }
                }
            }
        }

        if (isset($options['_id']) && $options['_id']) {
            $query->andWhere($table . '.' . 'id <> ' . $options['_id']);
        }

        $order = $table . '.' . $this->default_order;
        if (isset($options['order']) && $options['order']) {
            $order = $options['order'];
        }

        $limit = $this->default_limit;
        if (isset($options['limit']) && $options['limit']) {
            $limit = $options['limit'];
        }

        $page = 1;
        if (isset($options['page']) && $options['page']) {
            $page = $options['page'];
        }

        $offset = $limit * ($page - 1);

        $query = $this->afterAttr($query, $options);

        if (isset($options['count']) && $options['count']) {
            return $query->count();
        }
        //
        $data = $query->orderBy($order)
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $data;
    }

    public function options($options = null)
    {
        $return = [];
        $data = (new \yii\db\Query())->select('*')
            ->from($this->getTableName())
            ->orderBy($this->default_order)
            ->all();
        $value = isset($options['value']) ? $options['value'] : 'name';
        $key = isset($options['key']) ? $options['key'] : 'id';
        $return = array_column($data, $value, $key);
        if (isset($options['promt']) && $options['promt']) {
            $promt = ($options['promt'] === true) ? array('' => $this->promt) : array('' => $options['promt']);
            $return = $promt + $return;
        }
        return $return;
    }

    public function optionsCache($options = null)
    {
        $cache = Yii::$app->cache;
        if ($cache) {
            $key = 'cache_table_' . $this->getTableName();
            $data = $cache->get($key);
            if ($data === false) {
                $data = $this->options($options);
                $cache->set($key, $data);
            }
            return $data;
        }
        return [];
    }

    public function getAllCached()
    {
        $cache = Yii::$app->cache;
        if ($cache) {
            $key = 'cache_tableall_' . $this->getTableName();
            $data = $cache->get($key);
            if ($data === false) {
                $tg = static::find()->all();
                if ($tg) foreach ($tg as $item) {
                    $data[$item['id']] = $item;
                }
                $cache->set($key, $data);
            }
            return $data;
        }
        return [];
    }

    public function beforeAttr($query, &$options)
    {
        return $query;
    }

    public function afterAttr($query, &$options)
    {
        return $query;
    }

    public static function getNameByKey($id, $key, $template = '')
    {
        $model = self::findOne($id);
        if ($model) {
            if (is_array($key)) {
                if ($template) {
                    foreach ($key as $k) {
                        $template = str_replace("{$k}", $model->$k, $template);
                    }
                } else {
                    foreach ($key as $k) {
                        $template = $template ? $template . '.' . $model->$k : $model->$k;
                    }
                }
                return $template;
            }
            return $model->$key;
        }
        return null;
    }

    public function show($attribute)
    {
        switch ($attribute) {
            case 'created_at':
                return $this->$attribute > 0 ? date('d-m-Y', $this->$attribute) : '';;
                break;
            case 'updated_at':
                return $this->$attribute > 0 ? date('d-m-Y', $this->$attribute) : '';
                break;
        }
        return $this->$attribute;
    }

    function afterSave($insert, $changedAttributes)
    {
        \Yii::$app->cache->delete('cache_tableall_' . $this->getTableName());
        \Yii::$app->cache->delete('cache_table_' . $this->getTableName());
        return parent::afterSave($insert, $changedAttributes);
    }

    public static function loadShowAll()
    {
        $model = new self();
        return $model;
    }

    function setAttributeShow($attr)
    {
        $this->setAttributes($attr, false);
    }

    function getTableName()
    {
        return static::tableName();
    }

    static function getOneMuch($id)
    {
        $tgs = (new static)->getAllCached();
        return isset($tgs[$id]) ? $tgs[$id] : false;
    }

    /*
	    Get class constants by token.
	    If you set constants with same prefix, like:
		MY_STATUS_1
		MY_STATUS_2
		MY_STATUS_3

	    , you can get it by calling
	    Class::getConstants('MY');
	    or
	    Class::getConstants('MY_STATUS');
	*/
    public static function getConstants($token, $objectClass)
    {
        $tokenLen = strlen($token);

        $reflection = new ReflectionClass($objectClass);
        $allConstants = $reflection->getConstants(); //constants as array

        $tokenConstants = array();
        foreach ($allConstants as $name => $val) {
            if (substr($name, 0, $tokenLen) != $token) continue;
            $key = str_replace(Inflector::slug($token), '', Inflector::slug($name));
            $tokenConstants[$key] = $val;
        }
        return $tokenConstants;
    }
}
