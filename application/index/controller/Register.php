<?php
namespace app\index\controller;//命名空间

use think\Controller;//引入父类的命名空间

class Register extends Controller//创建Register控制器
{
  	public function index()//默认操作方法名
  	{
    	return $this->fetch();//使用fetch方法来获取解析后的模板内容
  	}
  	//处理註册逻辑
	public function doRegister()
    {
	$param = input('post.');//获取用户输入的数据
    $name = $param['user_name'];//将帐号存在$name变量中,方便之后调用
    $pwd = $param['user_pwd'];//将密码存在$pwd变量中,方便之后调用

    if ((!empty($name)) && (!empty($pwd)))
    {//如果用户名和密码都不为空
        $has = db('users')->where('user_name', $param['user_name'])->find(); //在数据库中查找用户名
        if(empty($has)){ //若在数据库中没有找到该用户
        $data=['user_name'=> $name,'user_pwd'=>md5($pwd)];  // =>是数组的赋值符号,简单来说就是=>符号来分隔键和值,左侧表示键,右侧表示值1
        $result = db('users')->insert($data); //指定users表,添加用户输入的帐号以及密码
          
        $this->redirect(url('login/index'));  //註册完后重定向到登录页面   
 		}
    	else{//若數據庫中已存在該用戶則給予提示
       		$this->error('該用户名已经存在'); 
    	}
    	#mysql_close();//关闭数据库	
	}
    else
    {//如果用户名或密码有空
      	$this->error('表单填写不完整');
    }
      
	}
}
  