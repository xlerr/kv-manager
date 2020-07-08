<?php

namespace kvmanager\controllers;

use kvmanager\models\KeyValue;
use kvmanager\models\KeyValueSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * KeyValueController implements the CRUD actions for KeyValue model.
 */
class KeyValueController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'sync'   => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new KeyValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    public function actionSync($id)
    {
        $model = $this->findModel($id);
        $model->pullConfig();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new KeyValue();

        $model->key_value_namespace = Yii::$app->getRequest()->get('key_value_namespace');
        $model->key_value_group     = Yii::$app->getRequest()->get('key_value_group');

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect([
                    'view',
                    'key_value_namespace' => $model->key_value_namespace,
                    'key_value_group'     => $model->key_value_group,
                    'id'                  => $model->key_value_id,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'view',
                'key_value_namespace' => $model->key_value_namespace,
                'key_value_group'     => $model->key_value_group,
                'id'                  => $model->key_value_id,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = KeyValue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
