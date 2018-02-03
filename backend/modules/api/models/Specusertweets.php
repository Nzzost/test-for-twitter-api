<?php

namespace backend\modules\api\models;

use Yii;

/**
 * This is the model class for table "specusertweets".
 *
 * @property int $id
 * @property int $specuser_id
 * @property string $tweet_id
 * @property string $tweet
 * @property string $hashtags
 * @property int $is_watch
 *
 * @property Specuser $specuser
 */
class Specusertweets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specusertweets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['specuser_id', 'tweet_id', 'tweet'], 'required'],
            [['specuser_id'], 'integer'],
            [['tweet', 'hashtags'], 'string'],
            [['tweet_id'], 'string', 'max' => 255],
            [['specuser_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specuser::className(), 'targetAttribute' => ['specuser_id' => 'u_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'specuser_id' => 'Specuser ID',
            'tweet_id' => 'Tweet ID',
            'tweet' => 'Tweet',
            'hashtags' => 'Hashtags',
            'is_watch' => 'Is Watch',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecuser()
    {
        return $this->hasOne(Specuser::className(), ['u_id' => 'specuser_id']);
    }
}
