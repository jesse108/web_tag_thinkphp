<?php
namespace Common\Util;

/**
 * 字符串工具方法
 * 
 * @author zhaojian jesse_108@163.com
 *
 */
class UtilString{
	const CHAR_MIX = 0;
	const CHAR_NUM = 1;
	const CHAR_WORD = 2;
	
	/**
	 * 生成随机字符串
	 * @param number $len 长度
	 * @param number $type 生成字符类型
	 * @return string 随机字串
	 */
	public static function GenRandomStr($len = 6,$type = self::CHAR_MIX){
		$random = '';
		for ($i = 0; $i < $len;  $i++) {
			$random .= self::_GenRandomChar($type,$i);
		}
		return $random;
	}
	
	public static function getRequestURI(){
		$server = $_SERVER;
		return $server['REQUEST_URI'];
	}
	
	//////////辅助函数
	private static function _GenRandomChar($type = self::CHAR_MIX,$index = 0){
		$random = '';
		switch ($type){
			case self::CHAR_NUM:
				if($index == 0){
					$random = chr(rand(49, 57));
				} else {
					$random = chr(rand(48, 57));
				}
				break;
			case self::CHAR_WORD:
				$key  = rand(0, 1);
				$random = $key ? chr(rand(65, 90)) : chr(rand(97, 122));
				break;
			case self::CHAR_MIX:
				$key  = rand(0, 2);
				if($key == 0){
					if($index == 0){
						$random = chr(rand(49, 57));
					} else {
						$random = chr(rand(48, 57));
					}
				} else if($key == 1){
					$random = chr(rand(65, 90));
				} else {
					$random = chr(rand(97, 122));
				}
				break;
		}
		return $random;
	}
	
	public static function GetFristPinyinOfStr($str){
		$str2PY = new str2PY();
		$fristChar = $str2PY->getInitials($str);
		return $fristChar;
	}
	
	/**
	 * 获取汉语拼音首字母
	 */
	public static function GetFristCharOfCNStr($str){
		$fristChar = self::GetFristPinyinOfStr($str);
		return strtoupper($fristChar[0]);
	}
	
	/**
	 * 
	 * @param array/str $data 带编码字符串
	 * @param boolean $encodeUniCode 中文是否编码
	 */
	public static function jsonEncode($data,$encodeUniCode = true){
		if($encodeUniCode){
			return json_encode($data);
		} else {
			return self::JsonEncodeUnicode($data);
		}
	}
	
	
	public static function JsonEncodeUnicode($object,$format = false){
		if(is_string($object)){
			return "\"{$object}\"";
		}
	
		if(is_numeric($object)){
			return $object;
		}
	
		$sep = '';
		if($format){
			$sep = "\n";
		}
	
		if(self::IsJsonObj($object)){
			$str = "";
				
			foreach ($object as $key => $value){
				$valueStr = self::JsonEncode($value,$format);
				$str .= "\"{$key}\":{$valueStr}," . $sep;
			}
			$str = trim($str,',');
			$str = "{{$sep}{$str}{$sep}}";
			return $str;
		}
	
		if(UtilArray::IsArrayValue($object)){
			$str = "";
			foreach ($object as $key => $value){
				$valueStr = self::JsonEncode($value,$format);
					$str .= "{$valueStr}," . $sep;
			}
			$str = trim($str,',');
			$str = "[{$sep}{$str}{$sep}]";
			return $str;
		}
	}
	
	/**
	 * 判断是否是json对象
	 * @param array $obj
	 */
	public static function IsJsonObj($obj){
		if(is_object($obj)){
			return true;
		}
	
		if(!UtilArray::IsArrayValue($obj)){
			return false;
		}
	
	
		$index = 0;
		foreach ($obj as $key => $value){
			if(!is_numeric($index)){
				return true;
			}
				
			if($key !== $index){
				return true;
			}
				
			$index++;
		}
		return false;
	}
	

    public static function FilterEOL($contents){
        if($contents){
            $contents = strtr($contents, array(
                "\n" => '',
                "\r" => '',
                "\t" => '',
            ));
        }
        return $contents;
    }

    public static function HigridCompressHTML($higrid_uncompress_html_source ) {

        $chunks = preg_split( '/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE );
        $higrid_uncompress_html_source = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记
        foreach ( $chunks as $c ) {
            if ( strpos( $c, '<pre' ) !== 0 ) {
                //[higrid.net] remove new lines & tabs
                $c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c );
                // [higrid.net] remove extra whitespace
                $c = preg_replace( '/\\s{2,}/', ' ', $c );
                // [higrid.net] remove inter-tag whitespace
                $c = preg_replace( '/>\\s</', '><', $c );
                // [higrid.net] remove CSS & JS comments
                $c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c );
            }

            $higrid_uncompress_html_source .= $c;
        }
        return $higrid_uncompress_html_source;
    }


    public static $sexTitle = array(
        self::SEX_MAN => '男',
        self::SEX_WOMAN => '女',
    );

    static public function XMLToArray($xml){
        $o = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return UtilArray::ObjectToArray($o);
    }
	
}








/**
 * Modified by http://iulog.com @ 2013-05-07
 * 修复二分法查找方法
 * 汉字拼音首字母工具类
 *  注： 英文的字串：不变返回(包括数字)    eg .abc123 => abc123
 *      中文字符串：返回拼音首字符        eg. 测试字符串 => CSZFC
 *      中英混合串: 返回拼音首字符和英文   eg. 我i我j => WIWJ
 *  eg.
 *  $py = new str2PY();
 *  $result = $py->getInitials('啊吧才的饿飞就好i就看了吗你哦平去人是他uv我想一在');
 */
class str2PY
{
	private $_pinyins = array(
			176161 => 'A',
			176197 => 'B',
			178193 => 'C',
			180238 => 'D',
			182234 => 'E',
			183162 => 'F',
			184193 => 'G',
			185254 => 'H',
			187247 => 'J',
			191166 => 'K',
			192172 => 'L',
			194232 => 'M',
			196195 => 'N',
			197182 => 'O',
			197190 => 'P',
			198218 => 'Q',
			200187 => 'R',
			200246 => 'S',
			203250 => 'T',
			205218 => 'W',
			206244 => 'X',
			209185 => 'Y',
			212209 => 'Z',
	);
	private $_charset = null;
	/**
	 * 构造函数, 指定需要的编码 default: utf-8
	 * 支持utf-8, gb2312
	 *
	 * @param unknown_type $charset
	 */
	public function __construct( $charset = 'utf-8' )
	{
		$this->_charset    = $charset;
	}
	/**
	 * 中文字符串 substr
	 *
	 * @param string $str
	 * @param int    $start
	 * @param int    $len
	 * @return string
	 */
	private function _msubstr ($str, $start, $len)
	{
		$start  = $start * 2;
		$len    = $len * 2;
		$strlen = strlen($str);
		$result = '';
		for ( $i = 0; $i < $strlen; $i++ ) {
			if ( $i >= $start && $i < ($start + $len) ) {
				if ( ord(substr($str, $i, 1)) > 129 ) $result .= substr($str, $i, 2);
				else $result .= substr($str, $i, 1);
			}
			if ( ord(substr($str, $i, 1)) > 129 ) $i++;
		}
		return $result;
	}
	/**
	 * 字符串切分为数组 (汉字或者一个字符为单位)
	 *
	 * @param string $str
	 * @return array
	 */
	private function _cutWord( $str )
	{
		$words = array();
		while ( $str != "" )
		{
			if ( $this->_isAscii($str) ) {/*非中文*/
				$words[] = $str[0];
				$str = substr( $str, strlen($str[0]) );
			}else{
				$word = $this->_msubstr( $str, 0, 1 );
				$words[] = $word;
				$str = substr( $str, strlen($word) );
			}
		}
		return $words;
	}
	/**
	 * 判断字符是否是ascii字符
	 *
	 * @param string $char
	 * @return bool
	 */
	private function _isAscii( $char )
	{
		return ( ord( substr($char,0,1) ) < 160 );
	}
	/**
	 * 判断字符串前3个字符是否是ascii字符
	 *
	 * @param string $str
	 * @return bool
	 */
	private function _isAsciis( $str )
	{
		$len = strlen($str) >= 3 ? 3: 2;
		$chars = array();
		for( $i = 1; $i < $len -1; $i++ ){
			$chars[] = $this->_isAscii( $str[$i] ) ? 'yes':'no';
		}
		$result = array_count_values( $chars );
		if ( empty($result['no']) ){
			return true;
		}
		return false;
	}
	/**
	 * 获取中文字串的拼音首字符
	 *
	 * @param string $str
	 * @return string
	 */
	public function getInitials( $str )
	{
		if ( empty($str) ) return '';
		if ( $this->_isAscii($str[0]) && $this->_isAsciis( $str )){
			return $str;
		}
		$result = array();
		if ( $this->_charset == 'utf-8' ){
			$str = iconv( 'utf-8', 'gb2312', $str );
		}
		$words = $this->_cutWord( $str );
		foreach ( $words as $word )
		{
			if ( $this->_isAscii($word) ) {/*非中文*/
				$result[] = $word;
				continue;
			}
			$code = ord( substr($word,0,1) ) * 1000 + ord( substr($word,1,1) );
			/*获取拼音首字母A--Z*/
			if ( ($i = $this->_search($code)) != -1 ){
				$result[] = $this->_pinyins[$i];
			}
		}
		return strtoupper(implode('',$result));
	}
	private function _getChar( $ascii )
	{
		if ( $ascii >= 48 && $ascii <= 57){
			return chr($ascii);  /*数字*/
		}elseif ( $ascii>=65 && $ascii<=90 ){
			return chr($ascii);   /* A--Z*/
		}elseif ($ascii>=97 && $ascii<=122){
			return chr($ascii-32); /* a--z*/
		}else{
			return '-'; /*其他*/
		}
	}

	/**
	 * 查找需要的汉字内码(gb2312) 对应的拼音字符( 二分法 )
	 *
	 * @param int $code
	 * @return int
	 */
	private function _search( $code )
	{
		$data = array_keys($this->_pinyins);
		$lower = 0;
		$upper = sizeof($data)-1;
		$middle = (int) round(($lower + $upper) / 2);
		if ( $code < $data[0] ) return -1;
		for (;;) {
			if ( $lower > $upper ){
				return $data[$lower-1];
			}
			$tmp = (int) round(($lower + $upper) / 2);
			if ( !isset($data[$tmp]) ){
				return $data[$middle];
			}else{
				$middle = $tmp;
			}
			if ( $data[$middle] < $code ){
				$lower = (int)$middle + 1;
			}else if ( $data[$middle] == $code ) {
				return $data[$middle];
			}else{
				$upper = (int)$middle - 1;
			}
		}
	}
	
}