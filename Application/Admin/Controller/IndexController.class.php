<?php
    /**
     * 后台登录控制器
     * @author zhiyuan.lin
     */
    
    namespace Admin\Controller;
    use Admin\Controller\CommonController;

	Class IndexController extends CommonController {
		
		//登陆页面视图
		Public function index(){
			$this->name = "欢迎回来 " . session('username');
			$this->display();
		}

		//退出登录
		Public function logout(){
			session(null);
			$this->redirect(MODULE_NAME . '/Login/index');
		}
		
	}

?>