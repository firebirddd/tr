<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $date
 * @property string $image
 * @property int $viewed
 * @property int $user_id
 * @property int $status
 * @property int $category_id
 *
 * @property ArticleTag[] $articleTags
 * @property Comment[] $comments
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'image' => 'Image',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleTags()
    {
        return $this->hasMany(ArticleTag::className(), ['article_id' => 'id']);
    }


    public function saveImage($imageName)
    {
        $this->image = $imageName;
        return $this->save(false);
    }


    public function deleteImage()
    {
        $imageUploadModel = new ImageUpload();
        $imageUploadModel->deleteCurrentImage($this->image);
    }


    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }


    public function getImage()
    {
        if(!$this->image)
            return '/images/no-image.png';
        return '/uploads/' . $this->image;
    }


    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }


    public function saveCategory($categoryId)
    {
        $category = Category::findOne($categoryId);

        if(!$categoryId)
            return false;


        $this->link('category', $category);
        return true;
    }


    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('article_tag', ['article_id' => 'id']);
    }


    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id' => 'id']);
    }


    public function getSelectedTags()
    {
        return $this->getTags()->select('id')->asArray()->all();
    }


    public function saveTags($tags)
    {
        if(!$tags || !is_array($tags))
            return false;


        $this->clearCurrentTags();

        foreach($tags as $tagId)
        {
            $tag = Tag::findOne($tagId);
            $this->link('tags', $tag);
        }
    }


    public function clearCurrentTags()
    {
        ArticleTag::deleteAll(['article_id' => $this->id]);
    }


    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date, 'long');
    }


    public static function getAll($pageSize = 5)
    {
        $query = Article::find();
        $count = $query->count();
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize
        ]);


        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $output['articles'] = $articles;
        $output['pagination'] = $pagination;
        return $output;
    }


    public static function getPopular()
    {
        return Article::find()->orderBy('viewed desc')->limit(3)->all();
    }


    public static function getRecent()
    {
        return Article::find()->orderBy('date asc')->limit(4)->all();
    }


    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;
        return $this->save();
    }


    public function getArticleComments()
    {
        return $this->getComments()->where(['status' => 1])->all();
    }


    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->update(false);
    }
}
