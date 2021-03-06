<?php 
/**
 * 网易云音乐个人信息爬虫
 * Author : RJ
 * Date   : 20170606
 * Notice : 网易云个人信息页面有hearder验证, 需要设置Referer、User-Agent
 */
include 'ganon.php'; //爬虫html解析文件

$url = "http://music.163.com/user/home?id=59986101";
// $html= file_get_contents($url);
// $fp = fopen('music.log','w');
// fwrite($fp, $html);
// fclose($fp);

$headers = array();
$headers[] = 'Referer: http://music.163.com/';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 UBrowser/6.2.3964.2 Safari/537.36';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$output = curl_exec($ch);
curl_close($ch);

//先用curl获取内容, 再把获取的内容传递进去解析
$html= str_get_dom($output); 
// $html= file_get_dom($url);

// echo "网易云用户 finally_y 信息:<br>\n";
// echo "动态", $html("#event_count")[0]->getPlainText(), "<br>\n";
// echo "关注", $html("#follow_count")[0]->getPlainText(), "<br>\n";
// echo "粉丝", $html("#fan_count")[0]->getPlainText(), "<br>\n";
$event_count = $html("#event_count")[0]->getPlainText();
$follow_count = $html("#follow_count")[0]->getPlainText();
$fan_count = $html("#fan_count")[0]->getPlainText();

$data=array();
$data['event_count'] = $event_count;
$data['follow_count'] = $follow_count;
$data['fan_count'] = $fan_count;

//写入配置文件
writeArr($data);

$result   = json_encode($data);
$callback = isset($_GET['callback']) ? $_GET['callback'] : 'null'; // 最好加上判空和默认值，以防拿不到参数
echo $callback."(".$result.")";


//=============================== 函数 ===============================

/**
 * [writeArr 写入配置文件方法]
 * @param  [type]  $arr      [要写入的数据]
 * @param  [type]  $filename [文件路径, 为空则取默认路径]
 * @param  boolean $truncate [是否合并数组,默认是]
 * @return [type]            [description]
 */
function writeArr($arr, $filename, $truncate=true) {
    $filename || $filename = 'Application/Home/Conf/cloudMusicInfo.php';

    //检查配置文件是否存在, 没有则创建
    if (!file_exists($filename)) {
        $fp=fopen($filename, "w");
        fwrite($fp, "<?php\r\nreturn array();");
        fclose($fp);
    }

    if ($truncate) {
        //注意不要include_once, 不然在config.php已经第一次引用, 这里用include_once引用直接返回true, 不返回数组
        $arr_old = include($filename);
        $arr = $arr + $arr_old;  //数组合并
    }

    return file_put_contents($filename, "<?php\r\nreturn " . var_export($arr, true) . ";");
}

?>