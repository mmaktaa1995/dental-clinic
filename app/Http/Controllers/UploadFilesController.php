<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadFilesController extends Controller
{
    public function store(Request $request, $resize = false): JsonResponse
    {
        // Validate folder name
        $folder = $request->get('folder');
        $type = $request->get('type', 'images');

        if (empty($folder) || empty($type)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid folder/type name!'
            ], 400);
        }

        // Validate file input
        if (!$request->hasFile('files')) {
            return response()->json([
                'success' => false,
                'message' => 'No files were uploaded!'
            ], 422);
        }

        $uploadedFiles = $request->file('files');
        $data = [];

        try {
            // Handle multiple or single file upload
            $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];

            foreach ($files as $file) {
                $data[] = $this->processAndStoreFile($file, $type, $folder);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processAndStoreFile(UploadedFile $file, $type, $folder): array
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $name = $fileName . '-' . time() . '-' . rand(10, 1000) . '.' . $ext;
        $path = "$type/$folder/$name";

        // Store file in the public disk
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        return [
            'file' => "/storage/$path",
            'file_name'=> $fileName,
            'type' => $file->getClientMimeType()
        ];
    }

    public function destroy(Request $request, $folder, $imageName): JsonResponse
    {
        try {
            // Check if the file exists
            $filePath = "images/$folder/$imageName";
            if (!Storage::disk('public')->exists($filePath)) {
                throw new FileNotFoundException("The file does not exist: $filePath");
            }

            // Delete the file
            Storage::disk('public')->delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الملف بنجاح!'
            ], 200);
        } catch (FileNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the file!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param $folder
     * @param $fileName
     * @param string $type
     *
     * @return StreamedResponse
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function show($folder, $fileName, $type)
    {
        $path = "$type/$folder/$fileName";

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
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
        return Storage::disk('public')->getMimetype("$type/$folder/$fileName");
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
