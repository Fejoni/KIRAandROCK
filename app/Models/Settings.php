<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key','value'];
    public $timestamps = false;

    public static function updatearray($forse=false)
    {
        global $rstconfig;
        if(!$rstconfig||$forse)
        {
            $items = self::all();
            $result=[];
            foreach ($items as $item)
                $result[$item->key]=$item->value;
            $rstconfig = $result;
        }
        return $rstconfig;
    }

    public static function getValue($key,$defoult='')
    {
        $conf = self::updatearray();
        return (isset($conf[$key]))?$conf[$key]:$defoult;
        /*$res = self::where('key',$key)->first();
        return (isset($res->value))?$res->value:$defoult;*/
    }

    public static function addValue($key,$value)
    {
        self::firstOrCreate(['key'=>$key,'value'=>$value]);
        self::updatearray(true);
    }

    public static function updateValue($key,$value)
    {
        if($value===null)
            $value = '';
        self::updateOrCreate(['key'=>$key],['value'=>$value]);
        self::updatearray(true);
    }

    public static function setValue($key,$value)
    {
        $model = self::where('key','=',$key)->first();
        if(!isset($model->value)){
            $model = new self;
            $model->key = $key;
        }
        $model->value = $value;
        $model->save();
        self::updatearray(true);
    }

    public static function getValues($values=false)
    {
        if($values)
            return self::whereIn('key',$values)->get();
        else
            return self::all();
    }
}
