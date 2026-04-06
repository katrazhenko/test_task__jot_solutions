<?php

/**
 * UserController handles the grid view for users with profiles.
 * Generated in Gii CRUD-style.
 */
class UserController extends Controller
{
    /**
     * @var string the default layout for the views.
     */
    public $layout = '//layouts/column1';

    /**
     * Lists all users with profiles in a grid view.
     * Supports AJAX-based filtering and pagination.
     */
    public function actionIndex()
    {
        $model = new UserSearch('search');
        $model->unsetAttributes(); // clear any default values

        if (isset($_GET['UserSearch'])) {
            $model->attributes = $_GET['UserSearch'];
            // Set virtual profile attributes
            if (isset($_GET['UserSearch']['profile_name']))
                $model->profile_name = $_GET['UserSearch']['profile_name'];
            if (isset($_GET['UserSearch']['profile_surname']))
                $model->profile_surname = $_GET['UserSearch']['profile_surname'];
            if (isset($_GET['UserSearch']['profile_status']) && $_GET['UserSearch']['profile_status'] !== '')
                $model->profile_status = $_GET['UserSearch']['profile_status'];
            if (isset($_GET['UserSearch']['profile_login_at']))
                $model->profile_login_at = $_GET['UserSearch']['profile_login_at'];
            if (isset($_GET['UserSearch']['profile_lang']))
                $model->profile_lang = $_GET['UserSearch']['profile_lang'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }
    public function actionGenerate()
    {
        if (!Yii::app()->request->isAjaxRequest && !Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $names = ['Ivan', 'Olena', 'Taras', 'Anna', 'Kateryna', 'Anton', 'Maksym', 'Svitlana', 'Denys', 'Yuliia'];
        $surnames = ['Shevchenko', 'Melnyk', 'Kovalenko', 'Bondarenko', 'Boyko', 'Tkachenko', 'Koval', 'Oliynyk', 'Lysenko'];
        
        $transaction = Yii::app()->db->beginTransaction();
        try {
            for ($i = 0; $i < 10; $i++) {
                $uniqueId = uniqid('', true) . random_int(1000, 9999);
                
                $user = new User();
                $user->email = "test_$uniqueId@example.com";
                $user->type = random_int(0, 1) ? User::TYPE_VIP : User::TYPE_STANDARD;
                $user->referer = random_int(0, 1) ? 'https://google.com' : null;
                $user->save(false);

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->name = $names[array_rand($names)];
                $profile->surname = $surnames[array_rand($surnames)];
                $profile->lang = random_int(0, 1) ? Profile::LANG_EN : Profile::LANG_UA;
                $profile->status = random_int(0, 9) > 1 ? Profile::STATUS_ACTIVE : Profile::STATUS_BANNED; // 90% active
                // Random login date in the past 30 days, or null
                if (random_int(0, 1)) {
                    $profile->login_at = date('Y-m-d H:i:s', time() - random_int(0, 30 * 86400));
                }
                $profile->save(false);
            }
            $transaction->commit();
            
            echo CJSON::encode(array('status' => 'success'));
            Yii::app()->end();
        } catch (Exception $e) {
            $transaction->rollback();
            echo CJSON::encode(array('status' => 'error', 'message' => $e->getMessage()));
            Yii::app()->end();
        }
    }
}
