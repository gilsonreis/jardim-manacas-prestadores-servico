<?php

namespace App\Controllers;

use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actionIndex(): Response
    {
        return $this->redirect('/dashboard');
    }
}
