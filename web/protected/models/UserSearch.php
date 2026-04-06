<?php

/**
 * UserSearch model - extends User for combined search across user + profile tables.
 * Used by the grid view to provide unified filtering.
 *
 * Virtual attributes from profile:
 * @property string $profile_name
 * @property string $profile_surname
 * @property integer $profile_status
 * @property string $profile_login_at
 * @property string $profile_lang
 */
class UserSearch extends User
{
    public $profile_name;
    public $profile_surname;
    public $profile_status;
    public $profile_login_at;
    public $profile_lang;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id, email, created_at, updated_at, referer, type', 'safe', 'on' => 'search'),
            array('profile_name, profile_surname, profile_status, profile_login_at, profile_lang', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Combined search across user and profile.
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('profile');
        $criteria->together = true;

        // User filters
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.created_at', $this->created_at, true);
        $criteria->compare('t.updated_at', $this->updated_at, true);
        $criteria->compare('t.referer', $this->referer, true);
        $criteria->compare('t.type', $this->type);

        // Profile filters
        $criteria->compare('profile.name', $this->profile_name, true);
        $criteria->compare('profile.surname', $this->profile_surname, true);
        if ($this->profile_status !== null && $this->profile_status !== '') {
            $criteria->compare('profile.status', $this->profile_status);
        }
        if (!empty($this->profile_login_at)) {
            $criteria->addCondition("DATE(profile.login_at) = :login_date");
            $criteria->params[':login_date'] = $this->profile_login_at;
        }
        $criteria->compare('profile.lang', $this->profile_lang);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 5,
            ),
            'sort' => array(
                'defaultOrder' => 't.id ASC',
                'attributes' => array(
                    'id' => array(
                        'asc' => 't.id ASC',
                        'desc' => 't.id DESC',
                    ),
                    'email' => array(
                        'asc' => 't.email ASC',
                        'desc' => 't.email DESC',
                    ),
                    'type' => array(
                        'asc' => 't.type ASC',
                        'desc' => 't.type DESC',
                    ),
                    'created_at' => array(
                        'asc' => 't.created_at ASC',
                        'desc' => 't.created_at DESC',
                    ),
                    'profile_name' => array(
                        'asc' => 'profile.name ASC',
                        'desc' => 'profile.name DESC',
                    ),
                    'profile_surname' => array(
                        'asc' => 'profile.surname ASC',
                        'desc' => 'profile.surname DESC',
                    ),
                    'profile_status' => array(
                        'asc' => 'profile.status ASC',
                        'desc' => 'profile.status DESC',
                    ),
                    'profile_login_at' => array(
                        'asc' => 'profile.login_at ASC',
                        'desc' => 'profile.login_at DESC',
                    ),
                    'profile_lang' => array(
                        'asc' => 'profile.lang ASC',
                        'desc' => 'profile.lang DESC',
                    ),
                    'referer' => array(
                        'asc' => 't.referer ASC',
                        'desc' => 't.referer DESC',
                    ),
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserSearch the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
