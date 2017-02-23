<?php
// +----------------------------------------------------------------------
// | PHP MVC FrameWork v1.0 在线翻译类 使用百度翻译接口 无需申请Api Key
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2099 http://qiling.org All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: qiling <70419470@qq.com> 2015年4月13日 下午2:22:15
// +----------------------------------------------------------------------
/**
 * 在线翻译类
 * @author qiling <70419470@qq.com>
 */
class Translate {
	/**
	 * 支持的语种
	 * @var ArrayAccess
	 */
	static $Lang = Array (
			'auto' => '自动检测',
			'ara' => '阿拉伯语',
			'de' => '德语',
			'ru' => '俄语',
			'fra' => '法语',
			'kor' => '韩语',
			'nl' => '荷兰语',
			'pt' => '葡萄牙语',
			'jp' => '日语',
			'th' => '泰语',
			'wyw' => '文言文',
			'spa' => '西班牙语',
			'el' => '希腊语',
			'it' => '意大利语',
			'en' => '英语',
			'yue' => '粤语',
			'zh' => '中文' 
	);
	static $Lang_rank = Array (   //author：zhiyuan.lin
			'ara',
			'de',
			'ru',
			'fra',
			'kor',
			'nl',
			'pt',
			'jp',
			'th',
			'wyw',
			'spa',
			'el',
			'it',
			'en',
			'yue',
			'zh' 
	);
	/**
	 * 获取支持的语种
	 * @return array 返回支持的语种
	 */
	static function getLang() {
		return self::$Lang;
	}

	static function getLang_rank() {
		return self::$Lang_rank;
	}
	/**
	 * 执行文本翻译
	 * @param string $text 要翻译的文本
	 * @param string $from 原语言语种 默认:中文
	 * @param string $to 目标语种 默认:英文
	 * @return boolean string 翻译失败:false 翻译成功:翻译结果
	 */
	static function exec($text, $from = 'zh', $to = 'en') {
		// http://fanyi.baidu.com/v2transapi?from=zh&query=%E7%94%A8%E8%BD%A6%E8%B5%84%E8%AE%AF&to=fra
		$url = "http://fanyi.baidu.com/v2transapi";
		$data = array (
				'from' => $from,
				'to' => $to,
				'query' => $text 
		);
		$data = http_build_query ( $data );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_REFERER, "http://fanyi.baidu.com" );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0' );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		
		$result = json_decode ( $result, true );
		
		// 出错状态码 999
		if ($result ['error']) {
			return false;
		}
		return $result ['trans_result'] ['data'] ['0'] ['dst'];
	}

	static function toPoetry($text) {
		$from = "zh";
		$to = self::$Lang_rank[rand(0,15)];
		while($from==$to){
			$to = self::$Lang_rank[rand(0,15)];
		}

		for ($i=0; $i < 4; $i++) {
			$text = self::exec_rank($text, $from, $to);
			//echo 'from:'.self::$Lang[$from] .' to:'.self::$Lang[$to]." et:{$text}".'<br/>';
			$from = $to;
			$to = self::$Lang_rank[rand(0,15)];
			while($from==$to){
				$to = self::$Lang_rank[rand(0,15)];
			}
		}

		$from!='zh' && $text = self::exec_rank($text, $from, "zh");
		return $text;
	}

	//author：zhiyuan.lin
	static function exec_rank($text, $from = 'zh', $to = 'en') {
		// http://fanyi.baidu.com/v2transapi?from=zh&query=%E7%94%A8%E8%BD%A6%E8%B5%84%E8%AE%AF&to=fra
		$url = "http://fanyi.baidu.com/v2transapi";

		$data = array (
				'from' => $from,
				'to' => $to,
				'query' => $text 
		);
		$data = http_build_query ( $data );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_REFERER, "http://fanyi.baidu.com" );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0' );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		
		$result = json_decode ( $result, true );
		
		// 出错状态码 999
		if ($result ['error']) {
			return false;
		}
		return $result ['trans_result'] ['data'] ['0'] ['dst'];
	}
}
//echo Translate::exec ( "你好，我是" );















