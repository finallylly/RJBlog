<!DOCTYPE html>
<html>
<head>
    <title>猜生日</title>
    <link rel="SHORTCUT ICON" href="http://nearby.wang/Public/Images/logo-icon.png" />
</head>

<?php
/**
 * name: RSA加密解密算法
 * author: RJ
 * encrypt demo: http://nearby.wang/calculate.php
 * decrypt demo: http://nearby.wang/calculate.php?code=密文
 * refer: http://blog.csdn.net/q376420785/article/details/8557266
 * 37*41=1517
 * 36*40=1440
 * 11*131=1(mod 1440)
 * 例如: 数字a
 * a^11%1517 = b
 * b^131%1517 = a
 * 公钥(1517, 11)
 * 私钥(1517, 131)
 */

//大数相乘算法   $A, $B均为字符串
function multipication($A, $B)
{
    $sResult = "";
    //反转字符串
    $A = strrev($A);
    $B = strrev($B);
    //建立temp变量
    $flag = array();
    for ($i = 0; $i < (strlen($A) + strlen($B) + 1); $i++) {
        $flag[$i] = "0";
    }
    //依次相乘叠加
    for ($i = 0; $i < strlen($A); $i++) {
        for ($j = 0; $j < strlen($B); $j++) {
            $flag[$i + $j]     = $flag[$i + $j] + ($A[$i] * $B[$j]) % 10;
            $flag[$i + $j + 1] = $flag[$i + $j + 1] + (int) (($A[$i] * $B[$j]) / 10);
        }
    }

    //再次相乘叠加
    for ($i = 0; $i < count($flag) - 1; $i++) {
        $flag[$i + 1] = $flag[$i + 1] + (int) ($flag[$i] / 10);
        $flag[$i]     = $flag[$i] % 10;
    }
    //去除高位无用的0；
    $mark = 0;
    for ($i = count($flag) - 1; $i >= 0; $i--) {
        //echo $flag[$i];
        if ($flag[$i] != 0 && $mark == 0) {
            $mark = true;
        }
        if ($mark == true) {
            $sResult = $sResult . $flag[$i];
        }
    }
    return $sResult;
}

//大数相除算法 $A为字符串 $B为正整数
function division($A, $B)
{
    while (strlen($A) > 9) {
        $temp_left  = substr($A, 0, 9);
        $temp_right = substr($A, 9);
        $A          = ($temp_left % $B) . $temp_right;
    }
    return $A % $B;
}

#####################################################################

/*$p $q $N 可按RSA规范自行配置*/
$p = 11;
$q = 131;
$N = 1517;

$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : 1;  //月份, 不补0
$day   = isset($_REQUEST['day']) ? $_REQUEST['day'] : 01;     //日期, 补0
$code  = isset($_REQUEST['code']) ? $_REQUEST['code'] : null; //密文

//加密
$bigNum = $num = $month . $day;
for ($i = 0; $i < $p - 1; $i++) {
    $bigNum = multipication($bigNum, $num);
}
$result = division($bigNum, $N);
echo '<div class="rsa">数字：' . $result . '</div>';

//解密
$bigNum = $num = $code != null ? $code : $result % $N;
for ($i = 0; $i < $q - 1; $i++) {
    $bigNum = multipication($bigNum, $num);
}
$result = division($bigNum, $N);
echo '<script type="text/javascript">console.log(' . $result . ');</script>';

?>

<style type="text/css">
body {background: dimgrey; }
select {height: 100px; width: 44%; float: left; font-size: 50px; margin: 3%; border-radius: 8px; outline: none; background: #607d8b; }
.sub {height: 150px; width: 94%; font-size: 50px; margin: 3%; border-radius: 25px; outline: none; background: navajowhite; }
.rsa {margin-top: 120px; height: 100px; font-size: 100px; text-align: center; color: #009688; }
</style>

<body>
    <img src="http://nearby.wang/Uploads/cover_img/QQ20160801002553.jpg" width="0" height="0">
    <form method="get" action="" style="margin-top:30px;">
        <select id="month" name="month">
            <?php
                for ($i = 1; $i <= 12; $i++) {
                    echo "<option value='{$i}' ";
                    if ($i == $month) {
                        echo "selected";
                    }
                    echo " >{$i}月</option>";
                }
            ?>
        </select>
        <select id="day" name="day">
            <?php
                for ($i = 1; $i <= 31; $i++) {
                    $dayformat = $i < 10 ? "0{$i}" : $i;
                    echo "<option value='{$dayformat}' ";
                    if ($i == $day) {
                        echo "selected";
                    }
                    echo " >{$i}日</option>";
                }
            ?>
        </select>
        <input class="sub" type="submit" name="sub" value=" 生成 ">
    </form>
</body>

</html>