<?php
	namespace Admin\Controller;
    use Admin\Controller\CommonController;
    
	Class SetInfoController extends CommonController{
		Public function index(){
			$this->attr = M('attr')->select();
			$this->display();
		}

		Public function setPsw(){
			$this->name = session("username");
			$this->display();
		}

		Public function runSetPsw(){
						
			$name = $_POST['name'];
			$psw = md5($_POST['psw']);
			$update = array(
					'id' => "1",
					'username' => $name,
					'password' => $psw
			);
			if(M('user')->save($update)){
				$this->success('修改成功', U(MODULE_NAME . '/SetInfo/setPsw'));
			}else{
				$this->error('修改失败');
			}
		}
	}
?>