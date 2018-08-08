<?php

namespace app\modules\admin\controllers;

use app\models\Comment;
use yii\web\Controller;

class CommentController extends Controller
{
    public function actionIndex()
    {

        $viewD['comments'] = Comment::find()->where(['status' => 0])->orderBy('id desc')->all();

        return $this->render('index', $viewD);
    }


    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);
        if($comment->delete())
            $this->redirect(['comment/index']);
    }


    public function actionAllow($id)
    {
        $comment = Comment::findOne($id);
        if($comment->allow())
            $this->redirect(['comment/index']);
    }
}