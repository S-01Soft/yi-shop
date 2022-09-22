<?php

namespace app\shop\logic\api;

use yi\User;
use yi\Verify;
use yi\Storage;
use support\exception\Exception;
use app\shop\model\api\FavoriteModel;
use app\shop\model\api\HistoryModel;
use app\area\model\api\AddressModel;

class UserLogic extends Logic 
{
    
    protected function initialize()
    {
        $this->static = \app\shop\model\api\UserModel::class;
        parent::initialize();
        $this->auth = get_user();
        $this->user = $this->auth->getUser();
    }
    
    public function getInfo()
    {
        $userinfo = $this->auth->getInfo();
        $payload = (object)[
            'userinfo' => $userinfo
        ];
        event('ShopGetUserinfo', $payload);
        return $payload->userinfo;
    }

    public function upload($file)
    {
        $option = [
            'type' => 'public',
            'scene' => '商城',
            'group' => '图片',
            'accept' => 'jpg,png,webp,jpeg'
        ];
        return Storage::config($option)->upload($file);
    }

    
    /**
     * 编辑用户资料
     */
    public function editInfo($attributes)
    {
        $allowEditFields = ['avatar', 'password', 'nickname'];
        $data = [];
        foreach ($attributes as $k => $v) {
            if (in_array($k, $allowEditFields)) $data[$k] = $v;
        }
        if (empty($data)) throw new Exception("没有任何修改");
        $userModel = $this->user;
        foreach ($data as $k => $v) {
            switch ($k) {
                case 'password': {
                    if (!empty($v)) {
                        if ($attributes[$k] != $attributes['repassword']) throw new Exception("两次输入的密码不一致");
                        $userModel->$k = $v;
                    }
                    break;
                }
                case 'avatar': {
                    $userModel->$k = $v;
                    break;
                }
                case 'nickname' : {
                    if (empty($v)) throw new Exception("昵称不能为空");
                    $payload = (object) [
                        'words' => $v,
                        'result' => true
                    ];
                    event('WordsSafeCheck', $payload);
                    if (!$payload->result) throw new Exception("昵称包含非法词");
                    $userModel->$k = $v;
                    break;
                }
            }
        }
        $userModel->save();
        return $this->getInfo();
    }

    
    /**
     * 手机验证码注册或登录
     */
    public function registerOrLogin($attributes)
    {
        extract($attributes);
        if (!Verify::check($mobile, $code, 'sms', 'shop_register')) {
            throw new Exception("验证码错误");
        }
        $auth = $this->auth;
        $user = \app\system\model\admin\UserModel::where('mobile', $mobile)->first();
        if (empty($user)) { // 注册
            $auth->register($mobile, '', '', $mobile);
            $payload = (object)[
                'user' => $auth->getUser()
            ];
            event('ShopUserRegisterSuccess', $payload);
            $auth->direct($auth->getUser());
            return $this->getInfo();
        } else { // 登录
            $auth->direct($user);
            return $this->getInfo();
        }
    }

    public function favorite($attributes)
    {
        extract($attributes);
        $state = empty($state) ? 0 : 1;
        $data = FavoriteModel::where('user_id', $this->user->id)->where('product_id', $id)->first();
        if ($state == 1) {
            if ($data) {
                throw new Exception("商品已收藏");
            }
            $data = FavoriteModel::create([
                'user_id' => $this->user->id,
                'product_id' => $id
            ]);
        } else {
            if ($data) {
                $data->delete();
            } else {
                throw new Exception("商品已取消收藏");
            }
        }
        return true;
    }
    
    public function getFavoriteList($page = 1)
    {
        return FavoriteModel::with(['product'])->where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function getHistoryList($page = 1)
    {
        return HistoryModel::with(['product'])->where('user_id', $this->user->id)->orderBy('updated_at', 'DESC')->paginate(10);
    }

    public function getAddress()
    {
        return AddressModel::where('user_id', $this->user->id)->orderBy('is_default', 'desc')->get();
    }

    public function getAddressInfo($id)
    {
        return AddressModel::where([['user_id', '=', $this->user->id], ['id', '=', $id]])->first();
    }

    public function editAddress($attributes)
    {
        extract($attributes);
        $user = $this->user;
        if ($is_default) {
            AddressModel::where('user_id', $user->id)->update(['is_default' => 0]);
        }
        if (isset($id)) {
            $model = AddressModel::where('id', $id)->where('user_id', $user->id)->first();
            if (empty($model)) $model = new AddressModel;  //不存在的直接新增
        } else {
            $model = new AddressModel;
        }
        $model->user_id = $user->id;
        $model->address_id = $address_id;
        $model->address = $address;
        $model->street = $street;
        $model->contactor_name = $contactor_name;
        $model->phone = $phone;
        $model->is_default = $is_default;
        return $model->save();
    }

    /**
     * 删除
     */
    public function delAddress($address_id)
    {
        return AddressModel::where('user_id', $this->user->id)->where('id', $address_id)->delete();
    }
}