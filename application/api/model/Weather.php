<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class Weather extends Model
{
    public function getName($weather_code='101010100')
    {
       	$json = file_get_contents('http://t.weather.sojson.com/api/weather/city/'.$weather_code);
      	$json=json_decode($json);
      	//Db::name('ins_county')->where('weather_code', $weather_code)->update(['weather_info' => $info]);
        //$res = Db::name('ins_county')->where('weather_code', $weather_code)->field("weather_info")->select();
      
      	$timeobj=$json->time;
       	$cityobj=$json->cityInfo->city;
      	$cityidobj=$json->cityInfo->cityId;
      	$shiduobj=$json->data->shidu;
      	$wenduobj=$json->data->wendu;
      	$pm25obj=$json->data->pm25; //float   
        $ganmaobj=$json->data->ganmao;  //建議
    	$qualityobj=$json->data->quality;  //輕度汙染
     
      	$info = "时间:".$timeobj."         城市:".$cityobj." 城市编码:".$cityidobj."   温度:".$wenduobj."℃"."      湿度:".$shiduobj."%RH"."  pm2.5:".$pm25obj."μg/m3"."  状态:".$qualityobj."           贴心建议:".$ganmaobj;
        
        // 1.创建信息数组
        $data = [];
        $data['weather_info'] = $info;
 
        // 2.更新数据
        Db::name('ins_county')->where('weather_code', $weather_code)->update($data);
  
      	return $info;
       
      
      	//$info = "時間:".$timeobj."   城市:".$cityobj."   城市編碼:".$cityidobj."\n溫度:".$wenduobj."℃"."   濕度:".$shiduobj."%RH"."\npm2.5:".$pm25obj."μg/m3"."   屬於".$qualityobj."   貼心建議:".$ganmaobj;
        //print $info;
    }
}