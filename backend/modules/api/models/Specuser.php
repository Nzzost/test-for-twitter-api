<?php

namespace backend\modules\api\models;

use Yii;

/**
 * This is the model class for table "specuser".
 *
 * @property int $u_id
 * @property string $name
 * @property string $id
 * @property string $secret
 *
 * @property Specusertwits[] $specusertwits
 */
class Specuser extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'specuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id', 'secret'], 'required'],
            [['name', 'id', 'secret'], 'string', 'max' => 255],
            [['secret'], 'unique'],
            [['secret'], 'secretValidate'],
            [['name', 'id', 'secret'], 'string'],
            [['id'], 'string', 'max' => 32 ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['id', 'name', 'secret'];

        return $scenarios;
    }

    public function secretValidate($attribute, $params)
    {
        if ($this->secret != sha1($this->id . $this->name))
            $this->addError($attribute, 'Wrong secret!');
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'u_id' => 'U ID',
            'name' => 'Name',
            'id' => 'ID',
            'secret' => 'Secret',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecusertwits()
    {
        return $this->hasMany(Specusertwits::className(), ['specuser_id' => 'u_id']);
    }
}
