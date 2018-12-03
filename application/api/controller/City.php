<?php
namespace app\api\controller;

use think\Controller;

//实现根据城市名称获取citycode
class City extends Controller
{

    public function read()
    {	
        $county_name = input('county_name');	//之後想辦法由微信獲取用戶輸入城市
        $model = model('City');
        $city_code = $model->getCity($county_name );
        if ($city_code) {
            $code =  '关於'.$county_name.'查询指令運行成功!';

        } else {
            $code = '抱歉,查询错误!';
        }
        $data = [
            '查询结果' => $code,
            'citycode' => $city_code
        ];

        return json($data);
      	
    }  

}