<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use app\models\Tag;
use app\models\ImageUpload;
use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSetImage($id)
    {
        $model = new ImageUpload();
        $viewD['model'] = $model;

        if(Yii::$app->request->isPost)
        {
            $article = $this->findModel($id);
            $file = UploadedFile::getInstance($model, 'image');

            $imageName = $model->uploadFile($file, $article->image);
            if($article->saveImage($imageName))
                $this->redirect(['view', 'id' => $article->id]);

        }


        return $this->render('image', $viewD);
    }


    public function actionSetCategory($id)
    {
        $article = $this->findModel($id);
        $viewD['article'] = $article;


        $categoryId = $article->category_id;
        $viewD['selectedCategory'] = $categoryId;


        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');
        $viewD['categories'] = $categories;


        if(Yii::$app->request->isPost)
        {
            $newCcategoryId = Yii::$app->request->post('category');
            if($article->saveCategory($newCcategoryId))
                return $this->redirect(['view', 'id' => $article->id]);
        }



        return $this->render('category', $viewD);
    }


    public function actionSetTag($id)
    {
        $article = $this->findModel($id);
        $viewD['article'] = $article;

        $viewD['selectedTags'] = ArrayHelper::getColumn($article->getSelectedTags(), 'id');
        $viewD['tagsList'] = ArrayHelper::map(Tag::find()->all(), 'id', 'title');


        if(Yii::$app->request->isPost)
        {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);
            $this->redirect(['view', 'id' => $article->id]);
        }


        return $this->render('tag', $viewD);

    }
}
