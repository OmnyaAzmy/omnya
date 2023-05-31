<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait MediaTrait
{
    /**
     * Handle request media files...
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected function handleRequestMediaFiles(Model $model, Request $request): void
    {
        // Handle uploaded media files...
        foreach ($model->formRequestFileKeys() as $collection => $form_inputs) {
            foreach ($form_inputs as $name) {
                $this->uploadMediaFile(
                    $model,
                    $request,
                    $name,
                    $collection
                );
            }
        }
    }

    /**
     * Handle file uploading...
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string   $name
     * @param  string?  $collection
     * @return void
     */
    private function uploadMediaFile(Model $model, Request $request, string $name, string $collection = null): void
    {
        if (! $request->hasFile($name) || ! $request->file($name)->isValid()) {
            return;
        }

        // Delete old file
        $model->getMedia(
            is_string($collection) ? $collection : 'default',
            ['form_key' => $name]
        )->each->delete();

        // file uploading...
        $res = $model
            ->addMediaFromRequest($name)
            ->withCustomProperties(['form_key' => $name])
            ->sanitizingFileName(function ($fileName) {
                $filename = pathinfo($fileName, PATHINFO_FILENAME);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);

                $filename = Str::slug($filename);

                if (! $filename) {
                    $filename = Str::random(16);
                }

                return strtolower($filename . '.' . $extension);
            })
            ->toMediaCollection($collection ?? 'default');
    }
}
