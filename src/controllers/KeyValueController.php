<?php

namespace kvmanager\controllers;

use common\helpers\UserHelper;
use kartik\depdrop\DepDropAction;
use kvmanager\models\KeyValue;
use kvmanager\models\KeyValueSearch;
use kvmanager\NacosApiException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * KeyValueController implements the CRUD actions for KeyValue model.
 */
class KeyValueController extends Controller
{
    public function init()
    {
        if (!Yii::$app->user->can('kvmanager')) {
            throw new ForbiddenHttpException('权限错误');
        }
    }

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

    public function actions()
    {
        return [
            'group-list' => [
                'class'            => DepDropAction::className(),
                'outputCallback'   => function ($namespace) {
                    $config = KeyValue::getAvailable();
                    $group  = $config[$namespace];

                    $data = [];
                    foreach ($group as $gp) {
                        $data[] = [
                            'id'   => $gp,
                            'name' => $gp,
                        ];
                    }

                    return $data;
                },
                'selectedCallback' => function ($namespace) {
                    $config = KeyValue::getAvailable();
                    $group  = $config[$namespace];

                    $defaultSelect = Yii::$app->request->get('default');

                    if (in_array($defaultSelect, $group)) {
                        return $defaultSelect;
                    } else {
                        return current($group);
                    }
                },
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->getRequest();

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
        $request = Yii::$app->getRequest();
        $model   = new KeyValue();

        $model->namespace = $request->get('namespace');
        $model->group     = $request->get('group');
        $model->type      = 'json';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (!KeyValue::permissionCheck($model->namespace, $model->group)) {
                    throw new ForbiddenHttpException('权限错误');
                }

                if ($model->insert(false)) {
                    return $this->redirect([
                        'index',
                        'namespace' => $model->namespace,
                        'group'     => $model->group,
                    ]);
                }
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
        $model     = $this->findModel($id);
        $namespace = $model->namespace;
        $group     = $model->group;

        $model->delete();

        return $this->redirect(['index', 'namespace' => $namespace, 'group' => $group]);
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
        if (($model = KeyValue::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!KeyValue::permissionCheck($model->namespace, $model->group)) {
            throw new ForbiddenHttpException('权限错误');
        }

        return $model;
    }
}
