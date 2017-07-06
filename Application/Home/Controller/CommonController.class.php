<?php
/**
 * Home模块公共控制器
 * @author zhiyuan.lin
 * @since 2016年7月24日
 */
namespace Home\Controller;

class CommonController extends \Common\Controller\CommonController{
    
    protected $pagenum=10;

    public function getPagenumSelect($num=0,$addAll=null){

        $pnum = $num==0 ? 'pagenum' : 'pagenum'.$num;  //根据传入参数去构建select
        $pagenumArr = array(
            '10' => 10,
            '20' => 20,
            '30' => 30,
            '50' => 50,
            '100' => 100,
        );
        if ($addAll && $addAll=='addAll') {
            $pagenumArr['10000000'] = 全部;
        }

        $str = '显示：';
        $str .= "<select name='{$pnum}'>";
        foreach($pagenumArr as $v){
            $sel = $v == $this->$pnum ? 'selected' : '';
            $str .= "<option value='{$v}' {$sel}>".$v."</option>";
        }
        $str .= "</select>&nbsp;条&nbsp;";
        return $str;
    }
    
    public function __construct(){
        parent::__construct();
       
        $this->pagenum = I('param.pagenum') ? intval(I('param.pagenum')) : $this->pagenum;

        $this->commentArr = M('comment')->order("id DESC")->where(array('status'=>1))->limit(10)->select();

        //网易云音乐 个人信息读取
        $cloudMusicInfo = include(APP_PATH.'Home/Conf/cloudMusicInfo.php');
        //右侧标签列表
        $cate = M('cate')->order('pid ASC')->select();

        //友情链接
        $link = M('url')->select();

        $this->assign(array(
            'cloudMusicInfo' => $cloudMusicInfo,
            'cate' => $cate,
            'link' => $link,
            ));


    }
 
    //获取缓存名称
    public function getCacheName($arr=array()){
        $arr=(array)$arr;
        $temp = array();
        $temp['product_id'] = $this->product_id;
        $temp['controller_name'] = CONTROLLER_NAME;
        $temp['action_name'] = ACTION_NAME;
        $arr['appid'] && $temp['appid'] = $arr['appid'];
        $arr['begindate'] && $temp['begindate'] = $arr['begindate'];
        $arr['enddate'] && $temp['enddate'] = $arr['enddate'];
        $arr['date'] && $temp['date'] = $arr['date'];
        $arr['mapset'] && $temp['mapset'] = $arr['mapset'];
        $arr['platform'] && $temp['platform'] = $arr['platform']; 
        $arr['eventkey'] && $temp['eventkey'] = $arr['eventkey'];
        $arr['version'] && $temp['version'] = $arr['version'];
        $arr['topl'] && $temp['topl'] = $arr['topl'];
        $arr['secl'] && $temp['secl'] = $arr['secl'];
        
        ksort($temp);
        $str = http_build_query($temp);  
        return md5($str);
    }

}