<?php

namespace app\shop\controller\index;

use yi\User;

class Index extends Base
{
    public function jump()
    {
        $url = $this->request->get('url');
        $url = urldecode($url);
        if (!empty($url)) return redirect($url);
    }
}