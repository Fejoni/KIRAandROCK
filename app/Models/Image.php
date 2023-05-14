<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image as Images;

class Image extends Model
{
    protected $fillable = [
        'path', 'order', 'is_main',
    ];

    public function object()
    {
        return $this->morphTo();
    }

    public static function massDelete($ids)
    {
        $ids = explode(',',$ids);
        foreach(self::whereIn('id',$ids)->get() as $oImage)
            $oImage->remove();
    }

    public static function massAssign($ids, $iObjectId)
    {
        self::whereIn('id',$ids)->update(['object_id'=>$iObjectId]);
        foreach(self::whereIn('id',$ids)->get() as $oAssignedImage)
        {
            $oAssignedImage->makeVariants();
        }
    }

    public static function massDeleteByOrderAndType($ids,$types)
    {
        foreach(self::whereIn('order',$ids)->whereIn('is_main',$types)->get() as $oImage)
            $oImage->remove();
    }

    public function remove()
    {
        $this->deleteFile();
        $this->delete();
    }

    public function deleteFile()
    {
        if(!empty($this->path) && file_exists(base_path('public'.$this->path))){
            unlink(base_path('public'.$this->path));
        }
        if($this->object && isset($this->object->image_sizes))
        {
            $sBaseName = basename($this->path);
            foreach($this->object->image_sizes as $sSizeName=>$aSizes)
            {
                $sSizePath = str_replace($sBaseName,$sSizeName.'_'.$sBaseName,($this->path));
                if(!empty($sSizePath) && file_exists(base_path('public'.$sSizePath))){
                    unlink(base_path('public'.$sSizePath));
                }
            }
        }
    }

    public function set_image_by_url($url,$name)
    {
        $oImage = Images::make($url);
        $oImage->save(storage_path('app/public/icons/' . $name.'.png'),100);
        $this->path = '/storage/app/public/icons/' . $name .'.png';
        $this->is_main = 0;
        $this->order = 1;
        return $this;
    }

    public static function cropImage($image_file,$jCrop,$folder,$iResizeWidth=false,$iResizeHeight=false,$iMaxWidth=1920)
    {
        if(!is_string($image_file))
        {
            $oImage = Images::make($image_file->path());
            $sExt = $image_file->extension();
        }
        else
        {
            $oImage = Images::make($image_file);
            $aPath = explode('.',$image_file);
            $sExt = array_pop($aPath);
        }

        // Получаем размеры и тип изображения (число)
        $iCurrentWidth = $oImage->width();
        $iCurrentHeight = $oImage->height();
		$iCropResize = false;

        if(!is_null($jCrop))
        {
            //Получаем координаты для кропа изображения
            $aCoord		= json_decode($jCrop, true);
            $iCoordx	= $aCoord['x']>0		? round($aCoord['x'])		: 0;
            $iCoordy	= $aCoord['y']>0		? round($aCoord['y'])		: 0;
            $iWidth		= $aCoord['width']>0	? round($aCoord['width'])	: 0;
            $iHeight	= $aCoord['height']>0	? round($aCoord['height'])	: 0;

            if ($iCoordx + $iWidth > $iCurrentWidth) $iWidth = $iCurrentWidth - $iCoordx; // Если ширина выходного изображения больше исходного (с учётом x_o), то уменьшаем её
            if ($iCoordy + $iHeight > $iCurrentHeight) $iHeight = $iCurrentHeight - $iCoordy; // Если высота выходного изображения больше исходного (с учётом y_o), то уменьшаем её

			if($iResizeWidth && $iResizeHeight)
				$iCropResize = true;
        }
        else
        {
            //$iWidth = $oImage->width();
            //$iHeight = $oImage->height();
			if($iCurrentWidth < $iResizeWidth)
				$iResizeWidth = $iCurrentWidth;
			if($iCurrentHeight < $iResizeHeight)
				$iResizeHeight = $iCurrentHeight;

			$iResizeWidth != false	? $iWidth = $iResizeWidth : $iWidth = $iCurrentWidth;
			$iResizeHeight != false ? $iHeight = $iResizeHeight : $iHeight = $iCurrentHeight;

			$iCoordx = 0;
            $iCoordy = 0;
        }

        //if(is_null($jCrop))
            //$oImage->resize($iResizeWidth,$iResizeHeight);
        //$oImage->resize(585,439);

        if(!file_exists(public_path('uploads/'.$folder))){
            mkdir(public_path('uploads/'.$folder.'/'), 0755, true);
        }

		$oImage->crop($iWidth, $iHeight, $iCoordx, $iCoordy);
		if($iCropResize){
			$oImage->resize($iResizeWidth,$iResizeHeight);
		} else {
			if($oImage->width()>$iMaxWidth){
				$oImage->resize($iMaxWidth,null, function ($constraint) {
					$constraint->aspectRatio();
				});
			}
		}

        //$func = 'image'.$sExt; // Получаем функцию для сохранения результата
        //$sExt == 'jpeg' ? $sExt = 'jpg' : false;

		$name = str_random(10).'_'.time();
        $PreviewPath = '/uploads/'.$folder.'/' . $name .'.'.$sExt;
        if($oImage->save('uploads/'.$folder.'/' . $name .'.'.$sExt)){
			return $PreviewPath;
		}

        return false;
    }

    public static function saveWithoutChange($image_file,$folder)
    {
        if(!is_string($image_file))
        {
            $oImage = Images::make($image_file->path());
            $sExt = $image_file->extension();
        }
        else
        {
            $oImage = Images::make($image_file);
            $aPath = explode('.',$image_file);
            $sExt = array_pop($aPath);
        }
        if(!file_exists(public_path('uploads/'.$folder))){
            mkdir(public_path('uploads/'.$folder.'/'), 0755, true);
        }

        //$func = 'image'.$sExt; // Получаем функцию для сохранения результата
        //$sExt == 'jpeg' ? $sExt = 'jpg' : false;
        $name = str_random(12).'_'.time();
        $PreviewPath = '/uploads/'.$folder.'/' . $name .'.'.$sExt;
        if($oImage->save('uploads/'.$folder.'/' . $name .'.'.$sExt))
            return $PreviewPath;
        return false;
    }

    public function makeVariants()
    {
        $sPath = ltrim($this->path,'/');
        $oModel = $this->object;
        if(file_exists(public_path($sPath)) && $oModel && !empty($oModel->image_sizes))
        {
            $oImage = Images::make($sPath);
            $sBaseName = basename($sPath);
            foreach($oModel->image_sizes as $sSizeName=>$aSizes)
            {
                if(isset($aSizes[0]) && $aSizes[1])
                {
                    $oMedium = $oImage->resize($aSizes[0],$aSizes[1]);
                    $oMedium->save(str_replace($sBaseName,$sSizeName.'_'.$sBaseName,$sPath));
                }

            }
        }
    }
}
