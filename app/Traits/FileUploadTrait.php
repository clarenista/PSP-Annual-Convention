<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{

    public function uploadFile(Model $model, $name = 'file', $field = 'url')
    {

        if (request()->hasFile($name)) {
            $file = request()->file($name);
            $file_name = Str::slug(time() . "-" . $file->getClientOriginalName());
            $file->storeAs("{$model->type}/", $file_name);
            $model->update([
                $field => Storage::url("{$model->type}/{$file_name}"),
            ]);
        }
    }
}
