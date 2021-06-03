<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class UploadFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file_data' => 'nullable|image|mimes:jpeg,png|max:5120', // ファイルのバリデーションよしなに。
            'file'      => 'required|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
        ];
    }

    public function validationData()
    {
        $all = parent::validationData();

        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($this->has('file')) {
            // base64をデコード。プレフィックスに「data:image/jpeg;base64,」のような文字列がついている場合は除去して処理する。
            $data = explode(',', $this->get('file'));
            if (isset($data[1])) {
                $fileData = base64_decode($data[1]);
            } else {
                $fileData = base64_decode($data[0]);
            }

            // tmp領域に画像ファイルとして保存してUploadedFileとして扱う
            $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
            file_put_contents($tmpFilePath, $fileData);
            $tmpFile = new File($tmpFilePath);
            $file = new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0,
                true // Mark it as test, since the file isn't from real HTTP POST.
            );
            $all['file_data'] = $file;
        }

        return $all;
    }
}
