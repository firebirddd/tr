<?php



function d()
{
    foreach (func_get_args() as $obj)
    {
        echo'<pre>';
        print_r($obj);
        echo'</pre>';
    }

    die;
}


function v()
{
    foreach (func_get_args() as $obj)
    {
        echo'<pre>';
        var_dump($obj);
        echo'</pre>';
    }

    die;
}








// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
