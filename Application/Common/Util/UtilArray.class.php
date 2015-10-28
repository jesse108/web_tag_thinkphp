<?php
namespace Common\Util;

class UtilArray{
	/**
	 * 获取一个二维数组的一列数据
	 * 
	 * @param array $array 输入数组
	 * @param string $colKey 指定列
	 * @return array 
	 */
	public static function GetColumn($array,$colKey,$subName = null, $nullFilter = true){
		if(!$array || !is_array($array) || !$colKey){
			return null;
		}
		if(is_object($array)){
			$array = self::ObjectToArray($array);
		}
		
		$colArray = array();
		foreach ($array as $index => $value){
			if(is_object($value)){
				$curValue = $value->$colKey;
			} else if(is_array($value)){
				$curValue = $value[$colKey];
			} else {
				continue;
			}
			
			if(is_array($curValue) || is_object($curValue)){
				$curKey = Util_String::MD5($curValue);
			} else {
				$curKey = $curValue;
			}

            if($nullFilter){
                if(isset($curValue) && $curValue !== ''){
                    $colArray[$curKey] = $curValue;
                }
            }else{
                $colArray[] = $curValue;
            }

			if($subName && $value[$subName]){
				$subValues = self::GetColumn($value[$subName], $colKey,$subName);
				if($subValues){
					foreach ($subValues as $subIndex => $subValue){
						$colArray[$subValue] = $subValue;
					}
				}
			}
		}
		$colArray = array_values($colArray);
		return $colArray;
	}
	
	/**
	 * 常用返回数组数据判断
	 * 判断返回的数组数据是否正确
	 * 
	 */
	public static function IsArrayValue($data){
		if(!$data || !is_array($data) || empty($data)){
			return false;
		}
		return true;
	}
	
	/**
	 * 替换数组中的null值
	 * @param unknown $array
	 * @param string $replace
	 * @return string
	 */
	public static function ReplaceNullValue($array,$replace = ''){
		foreach ($array as $key => $value){
			if(is_array($value)){
				$value = self::ReplaceNullValue($value,$replace);
			} else if($value === null){
				$value = $replace;
			}
			$array[$key] = $value;
		}
		return $array;
	}
	
	/**
	 * 获取第一个元素
	 * 
	 * @param unknown $array
	 */
	public static function GetFristItem($array,$key = null){

        if(empty($array)) return false;

		$item = false;
		foreach ($array as $k => $one){
			if(!$item){
				$index = $k;
				$item = $one;
                break;
			}
		}
		
		if($key){
			if($key == 'index'){
				$item = $index;
			} else {
				$item = $item[$key];
			}
		}
		
		return $item;
	}
	
	/**
	 * 将数组按指定列的值 为关键字 构造新数组返回
	 * 注意 如果有多个数据   关键字相同将会覆盖
	 * 
	 * @param array $array 输入数组
	 * @param string $colKey 指定列名
	 * @return array 构造好的数组
	 */
	public static function AssColumn($array,$colKey = null,$asArray = false,$sub = ''){
		if(!$array || !is_array($array)){
			return null;
		}
		
		$newArray = array();
		foreach ($array as $index => $one){
			$key = $colKey ? $one[$colKey] : $one;
			if($sub && $one[$sub]){
				$one[$sub] = self::AssColumn($one[$sub],$colKey,$asArray,$sub);
			}
			
			if(isset($key) && $asArray){
				if($asArray === true){
					$newArray[$key][] = $one;
				} else {
					$newArray[$key][$one[$asArray]] = $one;
				}
				
			} else if(isset($key) && !isset($newArray[$key])){
				$newArray[$key] = $one;
			}
		}
		return $newArray;
	}

	public static function Trim($array){
		foreach ($array as $index => $one){
			if(is_array($one) || is_object($one)){
				$one = self::Trim($one);
			} else {
				$one = trim($one);
			}
			$array[$index] = $one;
		}
		return $array;
	}	
	
	public static function GroupInColum($array,$colKey){
		if(!$array || !is_array($array) || !$colKey){
			return $array;
		}
		
		$newArray = array();
		foreach ($array as $index => $one){
			$key = $one[$colKey];
			$newArray[$key][$index] = $one;
		}
		
		return $newArray;
	}
	
	/**
	 * 数据合并  相同键值 B数据会覆盖A数组
	 * @param array $arrayA  数组一
	 * @param array $arrayB  数组二
	 */
	public static function Merge($arrayA,$arrayB){
		$array = array();
		$arrayA = $arrayA ? $arrayA : array();
		$arrayB = $arrayB ? $arrayB : array();
		$array = $arrayA;
		foreach ($arrayB as $key => $value){
			if(is_numeric($key)){
				$array[] = $value;
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
	}
	
	/**
	 * 对象转化成数组
	 * @param obj $obj 对象
	 * @return array 转化后的数组
	 */
	public static function ObjectToArray($obj){
		$_arr = is_object($obj) ? get_object_vars($obj) :$obj;
		foreach ($_arr as $key=>$val){
			$val = (is_array($val) || is_object($val)) ? self::ObjectToArray($val):$val;
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	/**
	 * 对数组排序
	 * @param unknown $array
	 * @param string $order
	 * @param string $key
	 * @return multitype:
	 */
	public static function Sort($array,$key = null,$order = SORT_ASC){
		if(!self::IsArrayValue($array)){
			return array();
		}
		$keyArray = array();
		$sortedArray = array();
		
		//分配
		foreach ($array as $index =>$value){
			$currentKey = '';
			if(is_array($value)){
				if(!$key){
					$currentKey = $index;
				} else if(isset($value[$key])){
					$currentKey = $value[$key];
				}
			} else {
				$currentKey = $value;
			}
			
			$keyArray[$currentKey][] = $index;
		}
		
		
		///排序
		switch ($order){
			case SORT_DESC:
				krsort($keyArray);
				break;
			case SORT_ASC:
			default:
				ksort($keyArray);
				break;
		}
		
		//组装
		foreach ($keyArray as $indexArray){
			foreach ($indexArray as $index){
				$sortedArray[$index] = $array[$index];
			}
		}
		
		return $sortedArray;
	
	}
	
	
	public static function Remove($array,$indexs,$key = array()){
		if(!self::IsArrayValue($array)){
			return false;
		}
		foreach ($array as $index => $one){
			if($key){
				$checkValue = $one[$key];
			} else {
				$checkValue = $index;
			}
			
			if(in_array($checkValue, $indexs)){
				unset($array[$index]);
			}
		}
		return $array;
	}

    public static function OptionArray($a = array(), $c1, $c2) {
        if(empty($a))
            return array();

        $s1 = self::GetColumn($a, $c1);
        $s2 = self::GetColumn($a, $c2);
        if($s1 && $s2 && count($s1) == count($s2)) {
            return array_combine($s1, $s2);
        }

        return array();
    }

    public static function OptionColumn($a = array(), $c1) {
        if(empty($a))
            return array();

        $s2 = array_keys($a);
        $s1 = self::GetColumn($a, $c1);

        if($s1 && $s2 && count($s1) == count($s2)) {
            return array_combine($s2, $s1);
        }

        return array();
    }

    public static function ArrayFlip($array){

        $values = array();
        foreach($array as $index => $one){
            if(!$one) continue;

            if(is_array($one)){
                foreach($one as $o){
                    $values[$o] = $index;
                }
            }else{
                $values[$one] = $index;
            }
        }

        return $values;

    }

    static public function JsonEncode($return, $to_unicode = false){

        if(!$return) return '';
        if(is_string($return)) return $return;

        if($to_unicode){
            return json_encode($return);
        }
        $url_encode = self::_jsonEncodeNoUnicode($return);
        return urldecode(json_encode($url_encode));
    }

    /**
     * json_encode后的中文不编码成unicode, urlencode过程
     * @param array/string $return
     * @return string
     */
    static private function _jsonEncodeNoUnicode($return){
        if(is_array($return)){
            foreach ($return AS $inx => $one){
                $return[$inx] = self::_jsonEncodeNoUnicode($one);
            }
            return $return;
        }

        return urlencode($return);
    }

	/////////////////////数组格式化
	/**
	 * 格式化嵌套数组
	 */
	public static function FormatInTree($array,$keyName = 'id',$parentKey = 'parent_id',$subName = 'sub'){
		$treeObj = new UtilArrayTree($array);
		$treeObj->keyName = $keyName;
		$treeObj->parentName = $parentKey;
		$treeObj->subName = $subName;
		
		$tree = $treeObj->getTree();
		return $tree;
	}
	
	/**
	 * 在树形数组中寻找指定元素
	 *
	 * @param array $tree   输入树形数组
	 * @param str $keyValue 寻找Key值
	 * @param string $keyName 键值名称 默认为 id
	 * @param string $subName 子树名称
	 * @return  找到的元素
	 */
	public static function FindNodeInTree($tree,$keyValue,$keyName = 'id',$subName = 'sub'){
		if(!self::IsArrayValue($tree)){
			return false;
		}
	
		foreach ($tree as $index => $one){
			if($one[$keyName] == $keyValue){
				return $one;
			}
				
			if($one[$subName]){
				$subResult = self::FindNodeInTree($one[$subName], $keyValue,$keyName,$subName);
				if($subResult){
					return $subResult;
				}
			}
		}
	
		return false;
	}
	
	/**
	 * 判断一个树形结构是否有指定项
	 *
	 * @param array $tree 输入树形结构
	 * @param array $keyArray 指定ID
	 * @param string $keyName 键名
	 * @param string $subName 子树名
	 * @param string $selfMark 标记名
	 * @param string $subMark 子标记名
	 * @param boolean $remove 是否删除无标记数据
	 */
	public static function MarkTree(&$tree,$keyArray,$keyName = 'id',$subName = 'sub',$selfMarkName = 'mark',$subMarkName='sub_mark',$remove = true){
		if(!$tree){
			return false;
		}
	
		$hasMark = false;
	
		foreach ($tree as $index => $one){
			$selfMark = $subMark = false;
			if(in_array($one[$keyName], $keyArray)){
				$selfMark = true;
			}
				
			if($one[$subName]){
				$subMark = self::MarkTree($tree[$index][$subName], $keyArray,$keyName,$subName,$selfMarkName,$subMarkName,$remove);
			}
				
			if($subMark || $selfMark){
				$hasMark = true;
			}
				
			$tree[$index][$selfMarkName] = $selfMark;
			$tree[$index][$subMarkName] = $subMark;
				
			if($remove && !$selfMark && !$subMark){
				unset($tree[$index]);
			}
		}
	
		return $hasMark;
	}
	/////////////////////////////////////////

    static public function UniqueKeys($keys){
        $params = func_get_args();
        if(!empty($params)){
            if(is_array($params[1])){
                $params = $params[1];
            }
            $params = array_filter($params);
            $params = array_unique($params);
        }else{
            $params = array();
        }
        return $params;
    }

    static public function SortArray($a=array(), $s=array(), $key=null)
    {
        if ($key) $a = self::AssColumn($a, $key);
        $ret = array();
        foreach( $s AS $one )
        {
            if ( isset($a[$one]) )
                $ret[$one] = $a[$one];
        }
        return $ret;
    }
    static public function ToAssColumn($data, $keyToTitle = 'attr', $valueToTitle = 'title'){

        if(empty($data)) return array();

        $dataRows = array();
        foreach($data as $index => $value){
            $dataRow = is_array($value) ? $value : array();

            $dataRow[$keyToTitle]   = $index;
            $dataRow[$valueToTitle] = $value;

            $dataRows[$index] = $dataRow;
        }

        return $dataRows;
    }
}


class UtilArrayTree{
	public $data;
	public $keyName = 'id';
	public $parentName = 'parent_id';
	public $subName = 'sub';
	
	
	
	public function __construct($data){
		$this->data = $data;
	}
	
	public function getTree(){
		$nodes = $this->buildArrayTreeNodes();
		$datas = self::AssColumn($this->data,$this->keyName);
		
		$tree = array();
		foreach ($nodes as $node){
			$data = $node->data;
			$key = $data[$this->keyName];
			$parentKey = $data[$this->parentName];
			
			if(!$parentKey || !$datas[$parentKey]){
				$data[$this->subName] = $this->getSubArray($node);
				$tree[$key] = $data;
			}
		}
		return $tree;
		
	}
	
	public function buildArrayTreeNodes(){
		$datas = self::AssColumn($this->data,$this->keyName);
		
		$nodes = array();
		
		foreach ($datas as $data){
			$key = $data[$this->keyName];
			$parentKey = $data[$this->parentName];
			
			$node = $nodes[$key];
			
			if(!$node){
				$nodes[$key] = $node = new UtilArrayTreeNode();
			}
			
			$node->data = $data;
			
			if($parentKey && $datas[$parentKey]){
				$parentNode =  $nodes[$parentKey];
				
				if(!$parentNode){
					$nodes[$parentKey] = $parentNode = new UtilArrayTreeNode();
				}
				
				$parentNode->subNode[$key] = $node;
			}
		}
		
		return $nodes;
	}
	
	public function getSubArray($node){
		$array = array();
		
		foreach ($node->subNode as $subNode){
			$data = $subNode->data;
			if($subNode->subNode){
				$data[$this->subName] = $this->getSubArray($subNode);
			}
			$array[$data[$this->keyName]] = $data;
		}
		
		return $array;
	}
}

class UtilArrayTreeNode{
	
	public $data;
	public $subNode;
	
	public function __construct(){
		
	}
}