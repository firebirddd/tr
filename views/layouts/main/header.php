<?php
use yii\helpers\Html;
?>

<nav class="navbar main-menu navbar-default">
    <div class="container">
        <div class="menu-content">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="/public/images/logo.jpg" alt=""></a>
            </div>


            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="i_con">
                    <ul class="nav navbar-nav text-uppercase">

                        <?php if(Yii::$app->user->isGuest) : ?>
                            <li><a href="/auth/login">Login</a></li>
                            <li><a href="/auth/signup">Register</a></li>
                        <?php else : ?>
                            <li>
                                <a href="/auth/logout" class="btn btn-link" style="padding-top: 10px">
                                    logout(<?=Yii::$app->user->identity->name; ?>)
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
            <!-- /.navbar-collapse -->
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>
