<?php
use think\Db;
use think\Debug;

  //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = '123456';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }else{

        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        /*<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[FromUser]]></FromUserName>
<CreateTime>123456789</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
</xml>*/
        $postObj = simplexml_load_string( $postArr );
        //$postObj->ToUserName = '';
        //$postObj->FromUserName = '';
        //$postObj->CreateTime = '';
        //$postObj->MsgType = '';
        //$postObj->Event = '';
        // gh_e79a177814ed
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
                /*<xml>
                <ToUserName><![CDATA[toUser]]></ToUserName>
                <FromUserName><![CDATA[fromUser]]></FromUserName>
                <CreateTime>12345678</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[你好]]></Content>
                </xml>*/


            }
        }
        if( strtolower( $postObj->MsgType) == 'text'){
            $content = $postObj->Content;
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  = 'text';
                $cityname  = mb_substr($content,0,-2,"UTF-8");
 

     
     	
          		//$city_info_api='http://211.159.146.11/weather/'.
               //$city_code_api='http://211.159.146.11/city/北京'
          
         
          
    /*      	include("weather_cityId.php");
        
          		foreach ($weather_cityId as $k=>$v) {	//$k是城市名,$v為該城市之天氣編碼
               	if($cityname == $k)
                { 
                
        		}
    												}  */
                  
                $cityapi = file_get_contents('http://211.159.146.11/city/'.$cityname);  //傳入使用者輸入的城市名$cityname,透過city_api取得城市編碼
         		$citycode = json_decode($cityapi)->citycode;  //將取得的內容轉換為json格式,拿取其中citycode欄位裡面的內容(實際的城市編碼)
          
                $json = file_get_contents('http://211.159.146.11/weather/'.$citycode);	//將取得的城市編碼傳入weather_api,取得該地天氣信息
 				
                $content =json_decode($json);	//將內容轉換為json格式

                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
          

                echo $info;
                
        }

    }

