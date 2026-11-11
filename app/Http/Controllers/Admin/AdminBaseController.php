<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class AdminBaseController extends Controller
{
    /**
     * Save an uploaded image directly under public/images/{folder} or return normalized path input.
     */
    protected function resolveImage(
        Request $request,
        string $fileField,
        string $pathField,
        string $folder,
        ?string $existingPath = null,
        string $defaultPath = '/images/royal-collection-logo.png'
    ): string {
        if ($request->hasFile($fileField)) {
            $file = $request->file($fileField);
            $directory = public_path("images/{$folder}");
            File::ensureDirectoryExists($directory);

            $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'royal-asset';
            $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
            $filename = $baseName.'-'.Str::lower(Str::random(6)).'.'.$extension;
            $file->move($directory, $filename);

            return "/images/{$folder}/{$filename}";
        }

        $manualPath = trim((string) $request->input($pathField, ''));
        if ($manualPath !== '') {
            return str_starts_with($manualPath, 'http') || str_starts_with($manualPath, '/')
                ? $manualPath
                : '/'.$manualPath;
        }

        return $existingPath ?: $defaultPath;
    }

    /**
     * Generate a guaranteed unique slug for the given Eloquent model class.
     */
    protected function uniqueSlug(string $modelClass, string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'item';
        $slug = $base;
        $counter = 2;

        while (
            $modelClass::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
