<?php
/**
 * Created by PhpStorm.
 * User: TiberLex
 * Date: 02.07.2018
 * Time: 16:54
 */

namespace App\Traits;


trait OrderSort
{
    public function getOrderField()
    {
        return 'order';
    }

    public function setOrder($iNumber)
    {
        $sOrderField = $this->getOrderField();
        if(!empty($this->$sOrderField))
        {
            if($iNumber<$this->$sOrderField)
                self::where($sOrderField,'>=',$iNumber)->where($sOrderField,'<',$this->$sOrderField)->increment($sOrderField);
            if($this->$sOrderField<$iNumber)
                self::where($sOrderField,'>',$this->$sOrderField)->where($sOrderField,'<=',$iNumber)->decrement($sOrderField);
        }
        else
            self::where($sOrderField,'>=',$iNumber)->increment($sOrderField);
        $this->$sOrderField = $iNumber;
    }
}