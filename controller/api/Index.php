<?php

namespace app\shop\controller\api;

use support\exception\Exception;
use yi\exception\ValidateException;
use yi\User;
use yi\Verify;
use app\shop\validate\api\UserValidate;
use app\shop\logic\api\IndexLogic;
use app\shop\logic\api\UserLogic;

class Index extends Base
{

    public $noNeedLogin = ['*'];

    public function before()
    {
        $this->logic = \app\shop\logic\api\IndexLogic::instance();
        parent::before();
    }

    public function index()
    {
        return $this->success([], 'Hi');
    }

    public function login()
    {
        $params = $this->request->post();
        $loginWay = isset($params['loginWay']) ? intval($params['loginWay']) : 0;
        switch ($loginWay) {
            case 0 : // 账号密码登录
                try {
                    validate(UserValidate::class . '.login')->check($params);
                    $auth = get_user();
                    if (true === $auth->login($params['username'], $params['password'])) {
                        return $this->success($auth->getInfo());
                    }
                    return $this->error($auth->getError());
                } catch (ValidateException $e) {
                    return $this->error($e->getMessage());
                } catch (Exception $e) {
                    return $this->error($e->getMessage(), $e->getCode());
                }
                break;
            case 1 : // 手机验证码登录
                try {
                    validate([
                        'mobile' => 'require',
                        'code' => 'require'
                    ])->check($params);                    
                    return $this->success(UserLogic::instance()->registerOrLogin($params));
                } catch (ValidateException $e) {
                    return $this->error($e->getMessage());
                } catch (Exception $e) {
                    return $this->error($e->getMessage(), $e->getCode());
                }
                break;
        }
    }

    public function logout()
    {
        if (!$this->auth->isLogin()) return $this->success();
        $this->auth->logout();
        return $this->success();
    }

    public function appInfo()
    {
        $data = IndexLogic::instance()->getAppInfo();
        return $this->success($data);
    }

    public function page()
    {
        try {
            $id = $this->request->post('id');
            $data = $this->logic->getPage($id);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getConfig()
    {
        try {
            $type = $this->request->post('type');
            $code = $this->request->post('code', []);
     
            $whiteList = [];
            if ($type == 'wechat') $whiteList = ['app_id', 'mini_appid', 'open_appid'];
    
            $config = get_module_group_config('shop', 'third');

            $thirdConfig = ev('GetThirdConfig', $config['scene'], 'wechat');
            $result = [];
            foreach ($thirdConfig as $k => $v) {
                if (in_array($k, $whiteList) && in_array($k, $code)) $result[$k] = $v;
            }
            return $this->success($result);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getArea()
    {
        return $this->success(IndexLogic::instance()->getArea());
    }

    public function getVerifyCode()
    {
        try {
            $params = $this->request->post();
            $rule = [
                'mobile|手机号码' => 'require|mobile'
            ];
            validate($rule)->check($params);
            if (!Verify::send($params['mobile'], 'sms', null, 'shop_register')) {
                return $this->error("短信发送失败");
            };
            return $this->success();
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 热门搜索词
     */
    public function getHotWords()
    {
        try {
            $params = $this->request->post();
            $payload = (object)['data' => [], 'params' => $params ];
            event('ShopHotWords', $payload);
            return $this->success($payload->data);
        } catch (ValidateException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}