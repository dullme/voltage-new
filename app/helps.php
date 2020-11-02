<?php
/**
 * 默认的精度为小数点后两位
 * @param $number
 * @param int $scale
 * @return \Moontoast\Math\BigNumber
 */
function bigNumber($number, $scale = 2)
{
    return new \Moontoast\Math\BigNumber($number, $scale);
}

function getStringLength($solarPanelWidth, $specificationQuantity)
{
    return ceil($solarPanelWidth * $specificationQuantity * STRING_LENGTH_BUFFER);
}

function ceilDecimal($number, $ceil = 3){
    $str = '';
    for ($i = 1; $i <= $ceil; $i++) {
        $str .= 0;
    }
    $str = intval('1' . $str);

    return ceil($number * $str) / $str;
}

function _unsetNull($arr){
    if($arr !== null){
        if(is_array($arr)){
            if(!empty($arr)){
                foreach($arr as $key => $value){
                    if($value === null){
                        $arr[$key] = '';
                    }else{
                        $arr[$key] = _unsetNull($value);      //递归再去执行
                    }
                }
            }
        }else{
            if($arr === null){ $arr = ''; }         //注意三个等号
        }
    }else{ $arr = ''; }
    return $arr;
}

/**
 * 批量更新
 * @param $table 表名
 * @param array $multipleData 需要更新的数组
 * @return bool|int
 */
function updateBatch($table, $multipleData = [])
{
    try {
        if (empty($multipleData)) {
            throw new \Exception("数据不能为空");
        }
        $tableName = DB::getTablePrefix() . $table; // 表名
        $firstRow  = current($multipleData);

        $updateColumn = array_keys($firstRow);
        // 默认以id为条件更新，如果没有ID则以第一个字段为条件
        $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
        unset($updateColumn[0]);
        // 拼接sql语句
        $updateSql = "UPDATE " . $tableName . " SET ";
        $sets      = [];
        $bindings  = [];
        foreach ($updateColumn as $uColumn) {
            $setSql = "`" . $uColumn . "` = CASE ";
            foreach ($multipleData as $data) {
                $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                $bindings[] = $data[$referenceColumn];
                $bindings[] = $data[$uColumn];
            }
            $setSql .= "ELSE `" . $uColumn . "` END ";
            $sets[] = $setSql;
        }
        $updateSql .= implode(', ', $sets);
        $whereIn   = collect($multipleData)->pluck($referenceColumn)->values()->all();
        $bindings  = array_merge($bindings, $whereIn);
        $whereIn   = rtrim(str_repeat('?,', count($whereIn)), ',');
        $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
        // 传入预处理sql语句和对应绑定数据
        return DB::update($updateSql, $bindings);
    } catch (\Exception $e) {

        return false;
    }
}
