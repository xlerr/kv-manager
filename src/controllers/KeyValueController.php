<?php

namespace kvmanager\controllers;

use kvmanager\NacosApiException;
use kvmanager\models\KeyValue;
use kvmanager\models\KeyValueSearch;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    /**
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->getRequest();
        if (empty($request->get(KeyValue::$namespaceFieldName)) || empty($request->get(KeyValue::$groupFieldName))) {
            return $this->redirect([
                'index',
                KeyValue::$namespaceFieldName => KeyValue::getDefaultNamespace(),
                KeyValue::$groupFieldName     => KeyValue::getDefaultGroup(),
            ]);
        }

        $searchModel  = new KeyValueSearch();
        $dataProvider = $searchModel->search($request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws NacosApiException
     * @throws InvalidConfigException
     */
    public function actionSync($id)
    {
        $model = $this->findModel($id);
        $model->pullConfig();
        Yii::$app->getSession()->setFlash('success', '同步成功!');

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new KeyValue();

        $model->namespace = Yii::$app->getRequest()->get('namespace');
        $model->group     = Yii::$app->getRequest()->get('group');
        $model->type      = 'json';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect([
                    'index',
                    'namespace' => $model->namespace,
                    'group'     => $model->group,
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'index',
                'namespace' => $model->namespace,
                'group'     => $model->group,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionCleanCache($id)
    {
        $this->findModel($id)->cleanCache();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * @param $id
     *
     * @return KeyValue|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = KeyValue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
