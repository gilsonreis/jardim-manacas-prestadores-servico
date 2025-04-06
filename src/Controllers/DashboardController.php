<?php

namespace App\Controllers;

use yii\filters\AccessControl;

class DashboardController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}