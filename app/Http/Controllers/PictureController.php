<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Http\Requests\StorePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function __construct()
    {
      /**
       * 認証が必要
       */
      $this->middleware('auth')->except(['index', 'download']);
    }

    /**
     * 写真一覧
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $pictures = Picture::with(['owner'])->orderby(Picture::CREATED_AT, 'desc')->paginate();

      return $pictures;
    }

    /**
     * 写真投稿
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePicture $request)
    {
      // 投稿写真の拡張子を取得する
      $extension = $request->picture->extension();

      $picture = new Picture();

      // インスタンス生成時に割り振られたランダムなID値と
      // 本来の拡張子を組み合わせてファイル名とする
      $picture->filename = $picture->id . '.' . $extension;

      // S3 にファイルを保存する
      // テスト時は storage/framework/testing/disks/s3/ にファイルを保存する
      Storage::cloud()->putFileAs('', $request->picture, $picture->filename);

      // データベースエラー時にファイル削除を行うため
      // トランザクションを利用する
      DB::beginTransaction();

      try {
        Auth::user()->pictures()->save($picture);
        DB::commit();
      } catch (\Exception $extension) {
        DB::rollBack();

        // データベースとの不整合を避けるためアップロードしたファイルを削除
        Storage::cloud()->delete($picture->filename);
        throw $extension;
      }

      // リソースの新規作成なので
      // レスポンスは201(CREATED)を返却する
      return response($picture, 201);
    }

    public function download(Picture $picture)
    {
      // 写真の存在チェック
      if (! Storage::cloud()->exists($picture->filename)) {
        return abort(404);
      }

      $disposition = 'attachment; filename="' . $picture->filename . '"';
      $headers = [
          'Content-Type' => 'application/octet-stream',
          'Content-Disposition' => $disposition,
      ];

      return response(Storage::cloud()->get($picture->filename), 200, $headers);
    }
}
