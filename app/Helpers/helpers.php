<?php

// 編集後、composer.jsonに追記・「$ composer dump-autoload」を実行

/**
 * キロメートル単位の距離を格納した数値型から、画面表示する文字列を生成
 */
if (!function_exists('display_distance'))
{
    function display_distance($km = 0.0)
    {
        $shown_text = (($km == 0) ? '0m' : // ゼロ除算防止
            (($km >= 1) ? round($km) . 'km' : // キロメートル
                (($km >= 0.001) ? round($km * 1000) . 'm' : // メートル
                    round($km * 1000, ceil(log10(1 / $km * 1000)) - 2) . 'm' // メートル(小数→丸める)
                )
            )
        );

        return $shown_text;
    }
}