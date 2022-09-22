<?php

namespace app\shop\controller\api;

use app\shop\validate\api\UserValidate;
use support\exception\Exception;
use yi\exception\ValidateException;

class User extends Base 
{
    public $needLogin = ['*'];
    public $noNeedLogin = [];

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\UserLogic;
    }

    public function index()
    {
        $user = get_user()->getUser();
        if ($user) {
            $user->setVisible(['id', 'nickname', 'avatar', 'email', 'mobile', 'money', 'score', 'uid']);
            $user->avatar = empty($user->avatar) ? '/static/images/missing-face.png' : $user->avatar;
        }
        $payload = (object) [
            'userinfo' => $user
        ];
        event('ShopGetUserinfo', $payload);
        return $this->success($payload->userinfo);
    }

    
    public function editInfo()
    {
        $this->request->filter(['strip_tags']);
        $params = $this->request->post();
        try {
            $result = validate(UserValidate::class . '.editInfo')->check($params);
            return $this->success($this->logic->editInfo($params));
        } catch (ValidateException $e){
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function viewList()
    {
        return $this->success($this->logic->getHistoryList());
    }

    public function favorite()
    {        
        $params = $this->request->post();
        try {
            $data = $this->logic->favorite($params);
            return $this->success($data);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function favoriteList()
    {
        try {
            $page = (int)request()->get('page', 1);
            return $this->success($this->logic->getFavoriteList($page));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function upload()
    {
        try {
            $file = $this->request->file('file');
            $data = $this->logic->upload($file);
            return $this->success($data);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}