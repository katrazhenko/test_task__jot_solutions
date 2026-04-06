<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $referer
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Profile $profile
 */
class User extends CActiveRecord
{
    const TYPE_STANDARD = 'standard';
    const TYPE_VIP = 'vip';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'unique'),
            array('email, referer', 'length', 'max' => 255),
            array('type', 'in', 'range' => array_keys(self::getTypeOptions())),
            array('created_at, updated_at', 'safe'),
            // search
            array('id, email, created_at, updated_at, referer, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'referer' => 'Referer',
            'type' => 'User Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('profile');
        $criteria->together = true;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.created_at', $this->created_at, true);
        $criteria->compare('t.updated_at', $this->updated_at, true);
        $criteria->compare('t.referer', $this->referer, true);
        $criteria->compare('t.type', $this->type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
            'sort' => array(
                'defaultOrder' => 't.id DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array user type options
     */
    public static function getTypeOptions()
    {
        return array(
            self::TYPE_STANDARD => 'Standard',
            self::TYPE_VIP => 'VIP',
        );
    }

    /**
     * Set timestamps before save.
     */
    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');

        return parent::beforeSave();
    }
}
