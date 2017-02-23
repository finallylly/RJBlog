<?php
	namespace Home\TagLib;
    use Think\Template\TagLib;
    defined('THINK_PATH') or exit();

	//import('TagLib');
	Class TagLibHd extends TagLib{
		Protected $tags = array(
			'nav' => array('attr' => 'order', 'close' => 1)
		);
		Public function _nav($attr, $content){
			//$attr = $this->parseXmlAttr($attr);
			//$attr($attr);
			$str = <<<str
<?php
					\$_nav_cate = M('cate')->order("{$attr['order']}")->select();
					import('Class.Category', APP_PATH);
					\$Category = new \Category();
					\$_nav_cate = \$Category::catesortforlayer(\$_nav_cate);
					foreach(\$_nav_cate as \$_nav_cate_v):
						extract(\$_nav_cate_v);
					\$url = U('/c_' . \$id);
?>
str;
			$str .= $content;
			$str .= '<?php endforeach;?>';
			return $str;
		}
	}
?>