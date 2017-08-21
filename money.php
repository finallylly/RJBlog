<!DOCTYPE html>
<html>
<head>
    <title>公积金减税 VS 理财</title>
    <link rel="SHORTCUT ICON" href="./Public/Images/logo-icon.png" />
</head>

<?php

#公积金缴纳举个栗子：假设每个月多缴纳500元公积金可以减50元左右的税, 问题相当于:
# 1.多缴: 月多缴500元送50元 + 500元公积金年利息1.5%收益
# 2.少缴: 月存500元自己理财
# 
#送的50元也能拿去理财, 利润一样, 于是模型相当于求时间最优解: 50总收益+500年化1.5% VS 450自理收益
#
#REF：http://finance.sina.com.cn/money/lczx/2016-02-18/details-ifxprucs6184413.shtml

$hand = isset($_REQUEST['hand']) ? $_REQUEST['hand'] : 500;  //公积金多存
$gain = isset($_REQUEST['gain']) ? $_REQUEST['gain'] : 50;   //发工资减税
$rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : 10;   //只理财利率
$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : 5;    //自理财年限

#365日息 12月息 1年息
$conf = array(
  365 => '日回息理财',
  12  => '月回息理财',
  1   => '年回息理财'
  );

#计算
foreach ($conf as $k => $v) {
    $pow   = $k; #平方次数
    $total = 0;  #450元 自存理财总现金
    $more  = 0;  #50元  自存理财总现金
    $fund  = 0;  #500元 公积金定存总现金
    for ($i=$year*12; $i>0 ; $i--) {
        $coeff  = pow(1+$rate/100/$pow,$pow*$i/12);
        $total += ($hand-$gain)*$coeff;
        $more  += $gain*$coeff;
        $fund  += $hand*pow(1+1.5/100/1,1*$i/12); #公积金利率统一按年息1.5%算
    }
    echo "<div class='res'>$v<br>自理财($rate%年化): ", number_format($total+$more), "<br>公积金(1.5%年化): ", number_format($fund+$more), "<br>理财多赚利息: ", number_format($total-$fund), "</div><hr>";
}

?>

<style type="text/css">
body {font-size:41px;font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif;}
.out {width:582px;margin:0 auto;padding-top:10px;}
.res {margin:0 auto;width:750px;text-align:center;padding-top:5px;}
.tr  {height:60px;width:200px; font-size:50px;outline:none;padding:0px 10px;}
.tb  {position:relative;font-size:25px;width:500px;margin:0 auto;text-align:center;}
.sub {height: 110px;width: 94%;font-size:70px;margin:2% 3%;border-radius:25px;outline:none;color:white;background:#399139;cursor:pointer;border:0px; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif;}
.sub:hover {background:#489948;}
</style>

<body>
<a href="https://github.com/finallylly/RJBlog/blob/master/money.php"><img style="position:absolute;top:0;right:0;border:0;width:250px;" src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>

    <form method="get" action="" style="margin-top:30px;margin:0 3%;">
        <div class="out">
            <span class="tl">公积金多存(元)</span>
            <input class="tr" type="text" name="hand" value="<?php echo $hand; ?>">
            
        </div>
        <div class="out">
            <span class="tl">发工资减税(元)</span>
            <input class="tr" type="text" name="gain" value="<?php echo $gain; ?>">
        </div>
        <div class="out">
            <span class="tl">自理财利率(％)</span>
            <input class="tr" type="text" name="rate" value="<?php echo $rate; ?>">
        </div>
        <div class="out">
            <span class="tl">自理财年限(年)</span>
            <input class="tr" type="text" name="year" value="<?php echo $year; ?>">
        </div>
        <input class="sub" type="submit" name="sub" value=" 生成 ">
        <div class="tb">
            <a href="https://github.com/finallylly/RJBlog/blob/master/money.php" target="_black">
                <img margin="0 auto" src="./Uploads/cover_img/QQ20160801002553.jpg" width="150" height="150" align="middle">
            </a>
            <hr>
            Wechat ID：finallylly<br>
            Feedback：finallybad@gmail.com<br>
            Copyright © RJ All Rights Reserved. 
        </div>
    </form>
</body>
</html>