<?php
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
                $content  = mb_substr($content,0,-2,"UTF-8");

          	//	$city = array("北京", "上海", "廣州");
				$city = array("01" => "北京", "02" => "上海","03" => "天津","04" => "重庆","05" => "黑龙江","06" => "吉林",
                               "07" => "辽宁","08" => "内蒙古","09" => "河北","10" => "山西","11" => "陕西","12" => "山东",
                               "13" => "新疆","14" => "西藏","15" => "青海","16" => "甘肃","17" => "宁夏","18" => "河南",
                               "19" => "江苏","20" => "湖北","21" => "浙江","22" => "安徽","23" => "福建","24" => "江西",
                               "25" => "湖南","26" => "贵州","27" => "四川","28" => "广东","29" => "云南","30" => "广西",
                               "31" => "海南","32" => "香港","33" => "澳门","34" => "台湾");

          		foreach ($city as $value) {
               	if($content == $value)
                {
  					$content = $content."地区的天气状况为雾转浮尘:\n气温:-3~-9℃\t风向:东北风\t风力:微风\t气压:1023.9hPa\t相对湿度:18%\t降雨机率:6%";
                }
                }
				
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
