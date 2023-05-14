<?php
namespace App\Classes;
class EditorJS
{
    public static function render($content)
    {
        if(is_string($content))
            $content = json_decode($content);
        if(isset($content->blocks))
            return self::render_blocks($content->blocks);
        return '';
    }
    public static function render_blocks($blocks)
    {
        $result='';
        foreach ($blocks as $block)
        {
            $result.=self::render_block($block);
        }
        return $result;
    }
    public static function render_block($block)
    {
        if(method_exists(self::class,'render_block_'.$block->type))
        {
            $func='render_block_'.$block->type;
            return self::$func($block->data);
        }
        return '<pre>'.json_encode($block).'</pre>';
    }

    public static function render_block_paragraph($data)
    {
        return "<p>$data->text</p>";
    }
    public static function render_block_header($data)
    {
        return "<h$data->level>$data->text</h$data->level>";
    }
    public static function render_block_raw($data)
    {
        return $data->html;
    }
    public static function render_block_image($data)
    {
        //stretched
        //withBorder
        return "<figure>
                    <img src='".$data->file->url."' style=\"".($data->withBackground?'width:100%;':'')."\"/>
                    <figcaption>$data->caption</figcaption>
                </figure>";
    }
    public static function render_block_checklist($data)
    {
        /*
        {"items":[
            {"text":"punct 1","checked":true},
            {"text":"punct 2","checked":true},
            {"text":"punct 3","checked":false}
            ]
        }
        */
        $result = '<div class="checklist">';
        foreach($data->items as $item)
        {
            $result .= "<div class='checklist-item".($item->checked?' active':'')."'>$item->text</div>";
        }
        $result .= '</div>';
        return $result;
    }
    public static function render_block_list($data)
    {
        /*
        {
            "style":"ordered/unordered",
            "items":[
                {"content":"punct 1","items":[]},
                {"content":"punct 2","items":[]},
            ]
        }
        */
        $element = '';
        if($data->style == 'ordered')
            $element = 'ol';
        elseif($data->style == 'unordered')
            $element = 'ul';
        return self::list_woker($data->items,$element);
    }
    protected static function list_woker($items,$element='ul')
    {
        if(empty($items))
            return '';
        $result = "<$element>";
        foreach ($items as $item)
            $result .= "<li>$item->content".self::list_woker($item->items,$element)."</li>";
        $result .= "</$element>";
        return $result;
    }
    public static function render_block_embed($data)
    {
        /*
        {
            "service":"youtube",
            "source":"https:\/\/www.youtube.com\/watch?v=HrM6fkbwcXw",
            "embed":"https:\/\/www.youtube.com\/embed\/HrM6fkbwcXw",
            "width":580,
            "height":320,
            "caption":"capion"
        }
        */
        switch ($data->service)
        {
            default:
                return "<iframe width=\"$data->width\" height=\"$data->height\" src=\"$data->embed\" title=\"$data->caption\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
        }
        return '';
    }
    public static function render_block_table($data)
    {
        /*
         {
            "withHeadings":true,
            "content":[
                ["heading1","heading2"],
                ["row1","row2"]
            ]
         }
        */
        if(!count($data->content))
            return '';
        $result = "<table>";
        if($data->withHeadings)
            $result .= "<thead>";
        else
            $result .= "<tbody>";
        $isFirstRow = true;
        foreach ($data->content as $row)
        {
            $result .= "<tr>";
            $col_tag = $isFirstRow && $data->withHeadings?'th':'td';
            foreach ($row as $col)
            {
                $result .= "<$col_tag>$col</$col_tag>";
            }
            $result .= "</tr>";
            if($isFirstRow && $data->withHeadings)
            {
                $isFirstRow = false;
                $result .="</thead>".(count($data->content)>1?"<tbody>":'');
            }
        }
        $result .= (count($data->content)>1?"</tbody>":'')."</table>";
        return $result;
    }
    public static function render_block_test($data)
    {
        $sList = '';
        foreach ($data->items??[] as $index=>$datd)
            $sList.='<div class="repiter-element"><div class="repiter-element-text">'.($datd->text??'').'</div></div>';

        return'<section class="test">
                    <div class="container">
                        <div class="simple_text">'.($data->text??'').'</div><!--Простой текст-->
                        <img class="fn_img_src" src="'.($data->img_src??'').'" alt=""><!--Изображение-->
                        <div class="repiter-wrapper">'.$sList.'</div><!--Повторитель-->
                    </div>
                </section>';
    }
}
