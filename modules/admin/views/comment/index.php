<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php if($comments) : ?>
        <table class="table">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Author</td>
                    <td>Text</td>
                    <td>Actions</td>
                </tr>
            </thead>

            <tbody>
            <?php foreach($comments as $comment) :?>
                <tr>
                    <td> <?=$comment->id ?> </td>
                    <td> <?=$comment->user->name ?> </td>
                    <td> <?=$comment->text ?> </td>
                    <td>
                        <a class="btn btn-success" href="/admin/comment/allow?id=<?=$comment->id?>">
                            Allow
                        </a>

                        <a class="btn btn-danger" href="/admin/comment/delete?id=<?=$comment->id?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>
