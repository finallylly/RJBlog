<?php
    /**
     * 后台登陆控制器
     * @author Administrator
     *
     */
    namespace Admin\Controller;
	use Think\Controller;

	Class LoginController extends Controller{
		
		//登录页面视图
		Public function index(){
			$this->display();
		}

		//登录表单操作
		Public function login(){
			if(!IS_POST) halt('页面不存在');

			if(I('code','','strtolower') != session('verify')){
				$this->error('验证码错误');
			}

			$db = M('user');
			$user = $db->where(array('usename' => I('username')))->find();

			if(!$user || $user['password'] != I('password','','md5')){
				// print_r($user);
				$this->error('账号密码错误');
			}

			//最后一次登录时间与IP
			$data = array(
				'id' => $user['id'],
				'logintime' => time(),
				'loginip' => get_client_ip()
			); 

			$db->save($data);

			session('uid', $user['id']);
			session('username', $user['username']);
			session('logintime', date('Y-m-d H:i:s', $user['logintime']));
			session('loginip', $user['loginip']);
		
			redirect(__MODULE__);

		}

		Public function verify(){
			//http://www.thinkphp.cn/topic/13186.html 参考网址
			import('Class.Image',APP_PATH);
			$Image = new \Image();
			$Image::verify();
			
			//或者写成如下：
			// import('Class.Image',APP_PATH);
			// \Image::verify(); 
		}

	}


?>