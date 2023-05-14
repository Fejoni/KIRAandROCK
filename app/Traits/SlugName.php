<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Route;
/**
 * Created by PhpStorm.
 * User: TiberLex
 * Date: 30.01.2018
 * Time: 11:32
 */
trait SlugName
{
    public function generateSlug($sName)
    {
        $sSlug = Str::slug($sName);
        //$sSlug = str_slug($sName);
        return $this->adaptSlug($sSlug);
    }

    public function getSlugField()
    {
        return 'slug';
    }

    public function checkRoutes($sSlug)
    {
        $aUrls = array_keys(Route::getRoutes()->getRoutesByMethod()['GET']);
        foreach($aUrls as $url)
        {
            $sPattern = str_replace('/','\/',$url);
            $sPattern = preg_replace('/\{.*\}/','.*',$sPattern);
            if($sPattern!=='.*')
            {
                if(preg_match('/'.$sPattern.'/',$sSlug)===1)
                    return false;
            }
        }
        return true;
    }

    public function adaptSlug($sSlug)
    {
        $sKey = $this->primaryKey;
        $exeptId = $this->$sKey;
        $checkSlugRow = self::where($this->getSlugField(), $sSlug)->when(($exeptId!==null),function($query) use ($exeptId){
            return $query->where($this->primaryKey,'<>',$exeptId);
        })->first();
        if($checkSlugRow){
            $i = 1;
            while(true){
                if(!self::where($this->getSlugField(), $sSlug.'-'.$i)
                    ->when(($exeptId!==null),function($query) use ($exeptId){
                        return $query->where($this->primaryKey,'<>',$exeptId);
                    })
                    ->first()){
                    $sSlug .= '-'.$i;
                    break;
                }
                $i++;
            }
        }
        return $sSlug;
    }
}