<?php 

namespace app\api\controller;

use think\Controller;

class Weather extends Controller
{
    public function read()
    {
		$weather_code=input('weather_code');
    	$model=model('Weather');
    	$data=$model->getName($weather_code);
    	return json($data);

             /* 存在問題待解決 如何表示forecast[0]??
            $flobj=$json->forecast->fl; //風力
                $typeobj=$json->forecast->type; //天氣狀況,晴天/陰天...
                $noticeobj=$json->forecast->notice;	//願你用有比陽光明媚的心情/注意防曬...  */

      
      	

    }
}