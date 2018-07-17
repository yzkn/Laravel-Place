<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用

class CsvController extends Controller
{
    protected $encoding_utf8 = 'UTF-8';
    protected $encodings = 'ASCII,JIS,UTF-8,eucJP-win,SJIS-win';
    protected $extension_csv = 'csv';
    protected $db_header = array('desc', 'lat', 'lng');
    protected $locale_jajp = 'ja_JP.UTF-8';
    protected $mimetype_text = 'text/plain';
    protected $name_file = 'file';
    protected $view_csv = 'csv';

    public function import()
    {
        $auth_user = Auth::user();
        $param = ['user' => $auth_user];
        return view($this->view_csv, $param);
    }


    public function store(Request $request)
    {
        $auth_user = Auth::user();
        $param = ['user' => $auth_user];

        if (!$request->hasFile($this->name_file))
        {
            return view($this->view_csv, $param);
        }

        setlocale(LC_ALL, $this->locale_jajp);

        $uploaded_file = $request->file($this->name_file);

        if (!$uploaded_file->isValid())
        {
            return view($this->view_csv, $param);
        }

        if ($uploaded_file->getMimeType() !== $this->mimetype_text)
        {
            return view($this->view_csv, $param);
        }

        if ($uploaded_file->getClientOriginalExtension() !== $this->extension_csv)
        {
            return view($this->view_csv, $param);
        }

        if (!$uploaded_file->getClientSize() > 0)
        {
            return view($this->view_csv, $param);
        }

        $filepath = $uploaded_file->getRealPath();
        $file = new \SplFileObject($filepath); // SqlFileObjectには"\"を付けて呼び出す
        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );

        // ヘッダー検証用にデータベースから列を取得
        //

        // CSVファイルの読み込み
        $row_count = 1;
        foreach ($file as $row)
        {
            if ($row_count == 1)
            {
                // ヘッダー行を読み込んで、項目名や列数が正しいか確認

                // 文字コード
                // if(mb_detect_encoding(implode($row)) !== $this->encoding_utf8)
                //     return;

                // ヘッダー
                $csv_header = array();
                foreach ($row as $value) {
                    $csv_header[] = $value;
                }
                if($this->db_header !== $csv_header) // 順番が違ってもいい場合はarray_diff
                {
                    return view($this->view_csv, $param);
                }
            }
            else
            {
                // データ行を読み込む

                if(count($row) !== count($csv_header))
                {
                    return view($this->view_csv, $param);
                }

                $row_utf8 = array();
                foreach ($row as $item) {
                    $row_utf8[] = mb_convert_encoding($item, $this->encoding_utf8, $this->encodings);
                }

                // insert
                $laravel_place = new LaravelPlace();
                $auth_user = Auth::user();
                $laravel_place->desc = $row_utf8[0];
                $laravel_place->lat = $row_utf8[1];
                $laravel_place->lng = $row_utf8[2];
                $laravel_place->owner = '';
                $laravel_place->user_id = $auth_user->id;
                $laravel_place->save();
            }
            $row_count++;
        }

        return view($this->view_csv, $param);
    }
}
