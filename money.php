<!DOCTYPE html>
<html>
<head>
    <title>Money</title>
    <link rel="SHORTCUT ICON" href="./Public/Images/logo-icon.png" />
</head>

<?php

#公积金缴纳举个栗子, 每个月多缴纳500块公积金可以减50块左右的税, 问题相当于:
# 1.多缴: 月多缴500送50
# 2.少缴: 月自存500自理
# 
#送的50块也能拿去自理, 利润一样, 于是模型转换成求时间最优解: 50总收益 vs 450自理收益

$hand = isset($_REQUEST['hand']) ? $_REQUEST['hand'] : 10000;  //月存
$gain = isset($_REQUEST['gain']) ? $_REQUEST['gain'] : 0;      //减税
$rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : 10;    //利率
$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : 5;      //年限

#365日息 12月息 1年息
$conf = array(
  365 => '日回息',
  12  => '月回息',
  1   => '年回息'
  );

#计算
foreach ($conf as $k => $v) {
    $pow   = $k; #平方次数
    $total = 0;  #450自存自理总现金
    $more  = 0;  #50自存自理总现金
    for ($i=$year*12; $i>0 ; $i--) {
        $coeff  = pow(1+$rate/100/$pow,$pow*$i/12);
        $total += ($hand-$gain)*$coeff;
        $more  += $gain*$coeff;
    }
    echo "<div class='res'>$v<br>自理财总额: ", number_format($total+$more), "<br>公积金总额: ", number_format($hand*$year*12+$more), "<br>多赚利息额: ", number_format($total-$hand*$year*12), "</div><hr>";
}

?>

<style type="text/css">
body {font-size:28px;font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif;}
.out {width:425px;margin:0 auto;padding-top:10px;}
.res {margin:0 auto;width:400px;text-align:center;padding-top:5px;}
.tr  {height:35px;width:78px; font-size:23px;outline:none;padding:0px 3px;}
.sub {height: 80px;width: 94%;font-size:45px;margin:3%;border-radius:25px;outline:none;background:#97CBEF;cursor:pointer;border:0px; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; }
.sub:hover {background:#7CB8E2;}
</style>

<body>
    <div style="height:100%">
    <form method="get" action="" style="margin-top:30px;margin:0 3%;">
        <div class="out">
            <span class="tl">月存(元)</span>
            <input class="tr" type="text" name="hand" value="<?php echo $hand; ?>">
            &nbsp;
            <span class="tl">减税(元)</span>
            <input class="tr" type="text" name="gain" value="<?php echo $gain; ?>">
        </div>
        <div class="out">
            <span class="tl">利率(％)</span>
            <input class="tr" type="text" name="rate" value="<?php echo $rate; ?>">
            &nbsp;
            <span class="tl">年限(年)</span>
            <input class="tr" type="text" name="year" value="<?php echo $year; ?>">
        </div>
        <input class="sub" type="submit" name="sub" value=" 生成 ">
        <img src="./Uploads/cover_img/QQ20160801002553.jpg" width="0" height="0">
        <div style="position:relative;bottom:0px">
            <div style="position:relative;font-size:14px;width:300px;margin:0 auto;text-align:center;">
                Provide By RJ &nbsp;｜&nbsp; Wechat ID：finallylly
            </div>
        </div>
    </form>
    </div>
</body>

</html>