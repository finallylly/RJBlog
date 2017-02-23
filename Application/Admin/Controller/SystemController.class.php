<?php
	namespace Admin\Controller;
    use Admin\Controller\CommonController;
    
	Class SystemController extends CommonController{
		
		Public function verify(){
			$this->display();
		}

		Public function updateverify(){

			$file = MODULE_PATH .'/Conf/verify.php';
			if(writeArr($_POST,$file)){
				$this->success('修改成功',U(MODULE_NAME . '/System/verify'));
			}else{
				$this->error('修改失败');
			}

			// //die(CONF_PATH);
			// F('verify', $_POST,CONF_PATH);
			// echo "-----------------";
			// print_r(F('verify','',CONF_PATH));
			// die();
			// if (F('verify', $_POST, CONF_PATH)){
			// 	$this->success('修改成功', U(MODULE_NAME . '/System/verify'));
			// }else{
			// 	$this->error('修改失败');
			// }


		}
	}
?>