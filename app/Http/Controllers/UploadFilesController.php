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
        if ($request->file('files')) {
            if (is_array($request->file('files'))) {
                foreach ($request->file('files') as $file) {
                    $image = $file;
                    $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                    $name = time() . rand(10, 1000) . '.' . $ext;
                    $path = "images/$folder/$name";
                    \Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));
                    $data[] = ['path' => $path];
                }
            } else {
                $image = $request->file('files');
                $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                $name = time() . rand(10, 1000) . '.' . $ext;
                $path = "images/$folder/$name";
                \Storage::disk('public')->put($path, file_get_contents($image->getRealPath()));
                $data = ['path' => $path];
            }
            return response()->json($data);
        } else {
            return response()->json(['message' => 'No files added!'], 422);
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

        return response()->json(['message' => 'File Deleted Successfully!']);
    }

    /**
     * @param $folder
     * @param $imageName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function show($folder, $imageName)
    {
        $path = "images/$folder/$imageName";
        if (\Storage::disk('public')->exists($path)) {
            $file = \Storage::disk('public')->get($path);
            $size = \Storage::disk('public')->getSize($path);
        }
        else
            throw new FileNotFoundException("File not exists!");

        header('Content-Type:' . $this->getMime($imageName, $folder));
        header('Content-Length: ' . $size);
        $this->sendImage($imageName, $folder);
        return response()->json(['file' => $file]);
    }

    private function getImageRaw($fileName, $folder) {
        $path = asset(sprintf('storage/images/%s/%s', $folder, $fileName));
        return file_get_contents($path);
    }

    private function sendImage($fileName, $folder) {
        $path = asset(sprintf('storage/images/%s/%s', $folder, $fileName));
        readfile($path);
//		ob_end_flush();
        die;
    }

    /**
     * @param $fileName
     * @param $folder
     *
     * @return false|string
     * @throws \League\Flysystem\FileNotFoundException
     */
    private function getMime($fileName, $folder) {
        return \Storage::disk('public')->getMimetype("images/$folder/$fileName");
    }
}
