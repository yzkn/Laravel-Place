<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\LaravelPlace;

use Illuminate\Support\Facades\Auth; // 認証で使用

use Illuminate\Support\Facades\Log; // ログ出力で使用

class CsvController extends Controller
{
    protected $encoding_sjiswin = 'SJIS-win';
    protected $encoding_utf8 = 'UTF-8';
    protected $encodings = 'ASCII,JIS,UTF-8,eucJP-win,SJIS-win';
    protected $eol_before = "\n"; #  protected $eol_before = PHP_EOL;
    protected $eol_after = "\r\n";
    protected $extension_csv = 'csv';
    protected $filename_export = 'out.csv';
    protected $db_header = array('desc', 'owner', 'lat', 'lng');
    protected $locale_jajp = 'ja_JP.UTF-8';
    protected $mimetype_csv = 'text/csv';
    protected $mimetype_text = 'text/plain';
    protected $name_file = 'file';
    protected $view_csv_import = 'csv.import';
    protected $view_csv_export = 'csv.export';

    public function import()
    {
        Log::info('CsvController::import()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);

        $param = ['user' => $auth_user];
        return view($this->view_csv_import, $param);
    }

    public function export()
    {
        Log::info('CsvController::export()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);

        $param = ['user' => $auth_user];
        return view($this->view_csv_export, $param);
    }

    public function store(Request $request)
    {
        Log::info('CsvController::store()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            return redirect('/login');
        }
        Log::info('auth_user: '.$auth_user->id);

        $param = ['user' => $auth_user];

        if (!$request->hasFile($this->name_file))
        {
            return view($this->view_csv_import, $param);
        }

        setlocale(LC_ALL, $this->locale_jajp);

        $uploaded_file = $request->file($this->name_file);

        if (!$uploaded_file->isValid())
        {
            return view($this->view_csv_import, $param);
        }

        if ($uploaded_file->getMimeType() !== $this->mimetype_text)
        {
            return view($this->view_csv_import, $param);
        }

        if ($uploaded_file->getClientOriginalExtension() !== $this->extension_csv)
        {
            return view($this->view_csv_import, $param);
        }

        if (!$uploaded_file->getClientSize() > 0)
        {
            return view($this->view_csv_import, $param);
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
                    return view($this->view_csv_import, $param);
                }
            }
            else
            {
                // データ行を読み込む

                if(count($row) !== count($csv_header))
                {
                    return view($this->view_csv_import, $param);
                }

                $row_utf8 = array();
                foreach ($row as $item) {
                    $row_utf8[] = mb_convert_encoding($item, $this->encoding_utf8, $this->encodings);
                }

                // insert
                $laravel_place = new LaravelPlace();
                $laravel_place->desc = $row_utf8[0];
                $laravel_place->owner = $row_utf8[1];
                $laravel_place->lat = $row_utf8[2];
                $laravel_place->lng = $row_utf8[3];
                $laravel_place->user_id = $auth_user->id;
                $laravel_place->save();

                Log::info('place: '.implode(";", $laravel_place));
            }
            $row_count++;
        }

        return redirect('/home');
    }

    public function write(Request $request)
    {
        Log::info('CsvController::write()');

        $auth_user = Auth::user();
        if( $auth_user===NULL)
        {
            Log::info('auth_user: NULL');
            return view($this->view_csv_export);
        }
        Log::info('auth_user: '.$auth_user->id);

        setlocale(LC_ALL, $this->locale_jajp);

        $places = LaravelPlace::get($this->db_header)->toArray();
        $csvHeader = $this->db_header;
        array_unshift($places, $csvHeader);
        $stream = fopen('php://temp', 'r+b');
        foreach ($places as $place) {
            Log::info('place: '.implode(";", $place));
            fputcsv($stream, $place);
        }
        rewind($stream);
        $csv = str_replace($this->eol_before, $this->eol_after, stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, $this->encoding_sjiswin, $this->encoding_utf8);
        $headers = array(
            'Content-Type' => $this->mimetype_csv,
            'Content-Disposition' => 'attachment; filename="' . $this->filename_export . '"',
        );
        return \Response::make($csv, 200, $headers);
    }
}
