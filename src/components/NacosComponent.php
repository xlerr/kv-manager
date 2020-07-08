<?php

namespace kvmanager\components;

use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use kemanager\NacosApiException;
use kvmanager\models\BaseModel;
use Psr\Http\Message\ResponseInterface;
use xlerr\httpca\ComponentTrait;
use xlerr\httpca\RequestClient;
use function GuzzleHttp\Psr7\stream_for;

class NacosComponent extends RequestClient
{
    use ComponentTrait;

    const CONFIG_KEY = 'nacos_server_config';

    protected function getHandlerStack()
    {
        $stack = parent::getHandlerStack();

        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
            if ($response->getStatusCode() !== 200) {
                throw new NacosApiException($response->getStatusCode() . ': ' . $response->getReasonPhrase() . PHP_EOL . (string)$response->getBody());
            }

            $body = [
                'code'    => self::SUCCESS,
                'message' => 'ok',
                'data'    => (string)$response->getBody(),
            ];

            return $response->withBody(stream_for(json_encode($body)));
        }));

        return $stack;
    }

    /**
     * 获取配置
     *
     * @param BaseModel $model
     *
     * @return bool
     */
    public function pullConfig(BaseModel $model)
    {
        return $this->get('cs/configs', [
            RequestOptions::QUERY => [
                'tenant' => $model->{$model::$namespaceFieldName},
                'group'  => $model->{$model::$groupFieldName},
                'dataId' => $model->{$model::$keyFieldName},
            ],
        ]);
    }

    /**
     * 发布配置
     *
     * @param BaseModel $model
     *
     * @return bool
     */
    public function releaseConfig(BaseModel $model)
    {
        return $this->post('cs/configs', [
            RequestOptions::FORM_PARAMS => [
                'tenant'  => $model->{$model::$namespaceFieldName},
                'group'   => $model->{$model::$groupFieldName},
                'dataId'  => $model->{$model::$keyFieldName},
                'type'    => $model->{$model::$typeFieldName},
                'content' => $model->{$model::$valueFieldName},
            ],
        ]);
    }

    /**
     * 删除配置
     *
     * @param BaseModel $model
     *
     * @return bool
     */
    public function deleteConfig(BaseModel $model)
    {
        return $this->delete('cs/configs', [
            RequestOptions::QUERY => [
                'tenant' => $model->{$model::$namespaceFieldName},
                'group'  => $model->{$model::$groupFieldName},
                'dataId' => $model->{$model::$keyFieldName},
            ],
        ]);
    }
}
