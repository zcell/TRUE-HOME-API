<?php


namespace app\controllers\v1;


use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return parent::behaviors();
    }
}