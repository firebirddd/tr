<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = 'Create Article';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create( document.querySelector('#article-content'))
        .catch( error => {
            console.error( error );
        } );

</script>