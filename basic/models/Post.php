<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 2/5/15
 * Time: 4:54 PM
 */
namespace app\models;
use \yii\db\Expression;

class Post extends \yii\db\ActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function tableName()
    {
        return 'posts';
    }

    public static function primaryKey()
    {
        return array('id');
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Content',
            'created' => 'Created',
            'modified' => 'Updated',
        );
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            $this->created = new Expression('NOW()');
            $command = static::getDb()->createCommand("select max(id) as id from posts")->queryAll();
            $this->id = $command[0]['id'] + 1;
        }

        $this->modified = new Expression('NOW()');
        return parent::beforeSave($insert);
    }

    /*public function rules()
    {
        return array(
            array('title, body', 'required'),
        );
    }*/

    public function rules() {
        return [
            [['title','body'], 'required']
        ];
    }
}