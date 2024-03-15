<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Exception\ServiceException;
use Hyperf\DbConnection\Db;
use Hyperf\Guzzle\ClientFactory;

class IndexController extends AbstractController
{
    public function orders()
    {
        $body = $this->request->getParsedBody();

        $url = $body['url'];
        $params = $body['request_params'];
        try {
            $client = di()->get(ClientFactory::class)->create(['timeout' => 5]);
            $response = $client->post($url, ['json' => $params]);
            $response_code = $response->getStatusCode();
            $response_content = $response->getBody()->getContents();
        } catch (\Exception $e) {
            $response_content = $e->getMessage();
            return $this->response->fail($response_content);
        }

        return $this->response->success(['http_code'=>$response_code,'result'=>json_decode($response_content,true)]);
    }

    public function paymentStatus()
    {
        $body = $this->request->getParsedBody();

        $url = $body['url'];
        $headers = $body['header_params'];

        try {
            $client = di()->get(ClientFactory::class)->create(['timeout' => 5]);
            $response = $client->get($url, ['headers' => $headers]);
            $response_code = $response->getStatusCode();
            $response_content = $response->getBody()->getContents();
        } catch (\Exception $e) {
            $response_content = $e->getMessage();
            return $this->response->fail($response_content);
        }

        return $this->response->success(['http_code'=>$response_code,'result'=>json_decode($response_content,true)]);
    }

    public function callback()
    {
        $params = $this->request->all();
        $site_code = explode('-',$params['x_reference_no'])[0]??'';
        $callback_url = Db::table('site')->where('code',$site_code)->value('callback_url');
        if (!$callback_url) {
            logger()->error('站点配置错误',[$site_code.'站点callback_url为空']);
            return 'FAIL';
        }
        try {
            $client = di()->get(ClientFactory::class)->create(['timeout' => 5]);
            $response = $client->post($callback_url, ['json' => $params]);
            $response_code = $response->getStatusCode();
            $response_content = $response->getBody()->getContents();
        } catch (\Exception $e) {
            $response_code = $e->getCode();
            $response_content = $e->getMessage();
        }
        logger()->info('回调',[$params]);

        if ($response_code != 200 || $response_content != 'SUCCESS') {
            logger()->error('站点回调错误',[$response_content]);
            return 'FAIL';
        }
        return 'SUCCESS';
    }

    public function jump()
    {
        return response()->redirect('');
        $params = $this->request->all();
        try {
            $site_code = explode('-', $params['x_reference_no'])[0] ?? '';
            $redirect_url = Db::table('site')->where('code', $site_code)->value('redirect_url');
            if (!$redirect_url) {
                throw new ServiceException($site_code . '站点$redirect_url配置为空');
            }
        } catch (\Exception $e) {
            return response()->redirect('https://culti.ph');
        }

        return response()->redirect((string)$redirect_url);
    }
}
