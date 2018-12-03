<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class City extends Model
{
    public function getCity($county_name='北京')
    {	
     	$has = Db::name('ins_county')->where('county_name', $county_name)->find();
      	if($has)
        {
            //$res = Db::name('ins_county')->where('county_name', $county_name)->select();
            $res = Db::name('ins_county')->where('county_name', $county_name)->column('weather_code');  //先在查询数据库的county_name中查找城市,显示该城市的天气编号(weather_code)
            return $res[0];
        }
      	else
        {
          	return "查無此城市!";
        }
    }

    public function getCityList()
    {
        $res = Db::name('ins_county')->select();
        return $res;
    }

}