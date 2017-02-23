<?php
/**
 * 公共控制器
 * @author zhiyuan.lin
 * @since 2016年7月24日
 */
namespace Common\Controller;
use Think\Controller;
use Think\Page;
use Think\Model;

class CommonController extends Controller{
	
    /**
     * 获取分页对象
     * @param int $count 总数
     * @param int $pernum 每页数
     * @return \Think\Page
     */
    public function getPage($count=0,$pernum=20){
        $page = new Page($count,$pernum);
        return $page;
    }
   

}