<?php

namespace modules\post\backend\models;

use Yii;
use kalpok\behaviors\SluggableBehavior;
use kalpok\behaviors\TimestampBehavior;
use kalpok\file\behaviors\FileBehavior;
use kalpok\behaviors\CategoriesBehavior;

class Post extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'post';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            CategoriesBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
            [
                'class' => FileBehavior::className(),
                'groups' => [
                    'image' => [
                        'type' => FileBehavior::TYPE_IMAGE,
                        'rules' => [
                            'extensions' => ['png', 'jpg', 'jpeg'],
                            'maxSize' => 1024*1024,
                        ]
                    ],
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['summary', 'content'], 'string'],
            [['isActive', 'priority'], 'integer'],
            [['title', 'language'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'شناسه',
            'title' => 'عنوان',
            'summary' => 'خلاصه',
            'content' => 'محتوا',
            'language' => 'زبان',
            'slug' => 'Slug',
            'createdAt' => 'تاریخ ایجاد نوشته',
            'updatedAt' => 'آخرین بروزرسانی',
            'isActive' => 'نمایش در سایت',
            'priority' => 'اولویت',
        ];
    }

    public function getCats()
    {
        return $this->hasMany(Category::className(), ['id' => 'categoryId'])->viaTable('post_category_relation', ['postId' => 'id']);
    }

    public static function find()
    {
        return new PostQuery(get_called_class());
    }
}
