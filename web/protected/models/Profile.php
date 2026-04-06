<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $lang
 * @property string $login_at
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Profile extends CActiveRecord
{
    const LANG_EN = 'en';
    const LANG_UA = 'ua';

    const STATUS_BANNED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
            return 'profile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('user_id, name, surname', 'required'),
            array('user_id, status', 'numerical', 'integerOnly' => true),
            array('name, surname', 'length', 'max' => 255),
            array('lang', 'in', 'range' => array_keys(self::getLangOptions())),
            array('status', 'in', 'range' => array_keys(self::getStatusOptions())),
            array('login_at', 'safe'),
            // search
            array('id, user_id, name, surname, lang, login_at, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'name' => 'Name',
            'surname' => 'Surname',
            'lang' => 'Language',
            'login_at' => 'Last Login',
            'status' => 'Status',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('user');
        $criteria->together = true;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.surname', $this->surname, true);
        $criteria->compare('t.lang', $this->lang);
        $criteria->compare('t.login_at', $this->login_at, true);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 5,
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Profile the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array language options
     */
    public static function getLangOptions()
    {
        return array(
            self::LANG_EN => 'English',
            self::LANG_UA => 'Українська',
        );
    }

    /**
     * @return array status options
     */
    public static function getStatusOptions()
    {
        return array(
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_BANNED => 'Banned',
        );
    }

    /**
     * @return string status label
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : 'Unknown';
    }

    /**
     * @return string language label
     */
    public function getLangLabel()
    {
        $options = self::getLangOptions();
        return isset($options[$this->lang]) ? $options[$this->lang] : 'Unknown';
    }
}
