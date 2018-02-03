<?php

namespace backend\modules\api\controllers;

use backend\modules\api\models\Specuser;
use backend\modules\api\models\Specusertweets;
use Yii;
use yii\web\Response;
use backend\modules\app\TwitterApi;
class SpecUserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $test = sha1("TEST" . "kievtypical");
        echo $test ;
        exit();
        return $this->render('index');
    }

    /**
     * @return json on GET request
     *
     * GET: {endpoint}/add?id=...&user=..&secret=..
     * Paramaters description:
     * Id - Random 32-char string used as unique identifier of a request
     * User - Twitter username of an user that should be added to my list
     * Secret - Secret parameter to be used as security layer
     */
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = new Specuser();
        $user->scenario = Specuser::SCENARIO_CREATE;
        $user->attributes = Yii::$app->request->get();
        if ($user->validate()) {
            $user->save();
            return Yii::$app->response->statusCode = 200;
        } else {
            return ['status' => false, 'data' => $user->getErrors()];
        }
    }

    /**
     * @return json on GET request
     *
     * GET: {endpoint}/feed?id=...&secret=..
     * Paramaters description:
     * Id - Random 32-char string used as unique identifier of a request
     * Secret - Secret parameter to be used as security layer
     */

    public function actionFeed()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $secret = \Yii::$app->request->get('secret');
        $id = \Yii::$app->request->get('id');

        if ($secret && $id) {
            $user = Specuser::find(['secret' => $secret])->one();
            if ($user) {
                if ($secret != $this->checkSHA1($user->id, $user->name))
                    return ['error', 'access denied'];
                $api =  new TwitterApi();
                if (!$api)
                    return ['error', 'internal error'];
                $tweets = $api->feedRecords($user);
                if (!$tweets)
                    return ['error', 'internal error'];
                return $tweets;

            } else {
                return ['error', 'missing parameter'];
            }
        }
    }

    /**
     * @return array|int
     * GET: {endpoint}/remove?id=...&user=..&secret=..
     * Paramaters description:
     * Id - Random 32-char string used as unique identifier of a request
     * User - Twitter username of an user that should be added to my list
     * Secret - Secret parameter to be used as security layer
     */
    public function actionRemove()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $secret = Yii::$app->request->get('secret');
        $id     = Yii::$app->request->get('id');
        $name   = Yii::$app->request->get('name');

        if (!$secret && !$id && !$name)
            return ['error', 'missing parameter'];
        if ($secret != $this->checkSHA1($id, $name))
            return ['error', 'access denied'];
        $user = Specuser::find()->where([ 'id' => $id,
              'secret' => $secret,
        ])->one();

        if ($user) {
            $user->delete();
            return Yii::$app->response->statusCode = 200;
        } else {
            return ['error', 'internal error'];
        }
    }

    protected function checkSHA1($id, $name)
    {
        $check = sha1($id.$name);
        return $check;
    }
}
