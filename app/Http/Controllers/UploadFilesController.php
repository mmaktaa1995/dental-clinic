<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class UploadFilesController extends Controller
{
    public function store(Request $request, $resize = false)
    {
        $data = [];
        $folder = $request->get('folder');
        $type = $request->get('type', 'images');
        if ($request->file('files')) {
            if (is_array($request->file('files'))) {
                foreach ($request->file('files') as $file) {
                    $image = $file;
                    $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                    $name = time() . rand(10, 1000) . '.' . $ext;
                    $path = "$type/$folder/$name";
                    \Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));
                    $data[] = ['path' => "/storage/$path"];
                }
            } else {
                $image = $request->file('files');
                $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                $name = time() . rand(10, 1000) . '.' . $ext;
                $path = "$type/$folder/$name";
                \Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));
                $data = ['path' => "/storage/$path"];
            }
            return response()->json($data);
        } else {
            return response()->json(['message' => 'لا توجد ملفات مضافة!'], 422);
        }
    }

    /**
     * @param $folder
     * @param $imageName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function destroy($folder, $imageName)
    {
        if (\Storage::disk('public')->exists("images/$folder/$imageName"))
            \Storage::disk('public')->delete("images/$folder/$imageName");
        else
            throw new FileNotFoundException("File not exists!");

        return response()->json(['message' => 'تم حذف الملف بنجاح!']);
    }

    /**
     * @param $folder
     * @param $fileName
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function show($folder, $fileName, $type)
    {
        $path = "$type/$folder/$fileName";

        if (\Storage::disk('public')->exists($path)) {
            return \Storage::disk('public')->download($path);
        } else
            throw new FileNotFoundException("الملف غير موجود!");


    }

    /**
     * @param $fileName
     * @param $folder
     *
     * @return false|string
     * @throws \League\Flysystem\FileNotFoundException
     */
    private function getMime($fileName, $folder, $type)
    {
        return \Storage::disk('public')->getMimetype("$type/$folder/$fileName");
    }

    private function sendImage($fileName, $folder, $type)
    {
        $path = asset(sprintf('storage/%s/%s/%s', $type, $folder, $fileName));
        readfile($path);
//		ob_end_flush();
        die;
    }

    private function getImageRaw($fileName, $folder, $type)
    {
        $path = asset(sprintf('storage/%s/%s/%s', $type, $folder, $fileName));
        return file_get_contents($path);
    }
}
