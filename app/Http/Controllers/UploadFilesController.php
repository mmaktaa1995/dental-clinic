<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadFilesController extends Controller
{
    /**
     * Handle file uploads with validation and security measures.
     *
     * @param FileUploadRequest $request
     * @param bool $resize Whether to resize images (not implemented yet)
     * @return JsonResponse
     */
    public function store(FileUploadRequest $request, bool $resize = false): JsonResponse
    {
        $validated = $request->validated();
        $folder = $validated['folder'];
        $type = $validated['type'];
        $uploadedFiles = $request->file('files'); // This is already an array due to prepareForValidation
        $data = [];
        $errors = [];

        try {
            foreach ($uploadedFiles as $file) {
                try {
                    $data[] = $this->processAndStoreFile($file, $type, $folder);
                } catch (\Exception $e) {
                    $errors[] = ['file' => $file->getClientOriginalName(), 'error' => $e->getMessage()];
                    Log::error('Individual file upload failed', [
                        'file' => $file->getClientOriginalName(),
                        'folder' => $folder,
                        'type' => $type,
                        'error' => $e->getMessage(),
                        'trace' => config('app.debug') ? $e->getTraceAsString() : 'Trace hidden in production'
                    ]);
                }
            }

            if (empty($data) && !empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'All file uploads failed.',
                    'errors' => $errors
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => count($data) . ' file(s) uploaded successfully.' . (!empty($errors) ? ' Some files failed.' : ''),
                'data' => $data,
                'errors' => $errors
            ], 201);

        } catch (\Exception $e) {
            Log::error('General file upload processing failed', [
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Trace hidden in production'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload processing.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error.'
            ], 500);
        }
    }

    /**
     * Process and store a single uploaded file with security measures.
     *
     * @param UploadedFile $file
     * @param string $type
     * @param string $folder
     * @return array
     * @throws \Exception
     */
    private function processAndStoreFile(UploadedFile $file, string $type, string $folder): array
    {
        $originalName = $file->getClientOriginalName();
        $fileNameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());

        // Sanitize file name (remove special characters, limit length)
        $safeFileNameWithoutExtension = Str::slug(substr($fileNameWithoutExtension, 0, 100));
        $uniqueFileName = $safeFileNameWithoutExtension . '_' . time() . '_' . Str::random(5) . '.' . $extension;

        $storagePath = trim($type, '/') . '/' . trim($folder, '/');
        
        // Basic check for malicious content (can be expanded)
        if ($this->containsMaliciousContent($file)) {
            throw new \Exception("File '{$originalName}' contains potentially malicious content.");
        }

        // Store the file
        $storedPath = $file->storeAs($storagePath, $uniqueFileName, 'public');
        if (!$storedPath) {
            throw new \Exception("Failed to store file '{$originalName}'.");
        }

        Log::info('File uploaded successfully', [
            'original_name' => $originalName,
            'stored_name' => $uniqueFileName,
            'path' => $storedPath,
            'user_id' => auth()->id()
        ]);

        return [
            'original_name' => $originalName,
            'file_name' => $uniqueFileName,
            'file_path' => Storage::disk('public')->url($storedPath),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $extension,
        ];
    }

    /**
     * Basic check for malicious content in the file.
     * This is a rudimentary check and should be augmented with more robust solutions for production.
     *
     * @param UploadedFile $file
     * @return bool
     */
    private function containsMaliciousContent(UploadedFile $file): bool
    {
        $content = file_get_contents($file->getRealPath());

        // Check for PHP tags or common malicious patterns
        if (preg_match('/<\?(php|=)|eval\(|system\(|passthru\(|shell_exec\(|base64_decode\(/i', $content)) {
            Log::warning('Potential malicious content detected in upload', ['filename' => $file->getClientOriginalName()]);
            return true;
        }

        // For images, try to re-encode to strip potential payloads (requires GD or Imagick)
        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image/') && extension_loaded('gd')) {
            try {
                $image = @imagecreatefromstring($content);
                if ($image === false) {
                    Log::warning('Invalid image content detected', ['filename' => $file->getClientOriginalName()]);
                    return true; // Could be a malformed image or an attempt to hide code
                }
                imagedestroy($image);
            } catch (\Exception $e) {
                 Log::warning('Exception during image validation', ['filename' => $file->getClientOriginalName(), 'error' => $e->getMessage()]);
                return true; // Treat as suspicious
            }
        }
        return false;
    }

    /**
     * Delete an uploaded file.
     *
     * @param Request $request (to get 'type' from query param or body)
     * @param string $folder
     * @param string $fileName
     * @return JsonResponse
     */
    public function destroy(Request $request, string $folder, string $fileName): JsonResponse
    {
        // Sanitize folder and file name to prevent directory traversal
        $folder = basename(trim($folder));
        $fileName = basename(trim($fileName));
        $type = $request->input('type', 'other'); // Default to 'other' or get from request

        if (empty($folder) || empty($fileName) || empty($type)) {
            return response()->json(['success' => false, 'message' => 'Invalid path components.'], 400);
        }

        $filePath = trim($type, '/') . '/' . $folder . '/' . $fileName;

        try {
            if (!Storage::disk('public')->exists($filePath)) {
                throw new FileNotFoundException("File not found: {$filePath}");
            }

            if (Storage::disk('public')->delete($filePath)) {
                Log::info('File deleted successfully', [
                    'path' => $filePath,
                    'user_id' => auth()->id()
                ]);
                return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
            } else {
                throw new \Exception('Failed to delete the file from storage.');
            }
        } catch (FileNotFoundException $e) {
            Log::warning('Attempt to delete non-existent file', ['path' => $filePath, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting file', [
                'path' => $filePath,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Trace hidden in production'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the file.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error.'
            ], 500);
        }
    }

    /**
     * Show/Download an uploaded file.
     *
     * @param string $type
     * @param string $folder
     * @param string $fileName
     * @return StreamedResponse|JsonResponse
     */
    public function show(string $type, string $folder, string $fileName)
    {
        // Sanitize inputs to prevent directory traversal
        $type = basename(trim($type));
        $folder = basename(trim($folder));
        $fileName = basename(trim($fileName));

        if (empty($folder) || empty($fileName) || empty($type)) {
            return response()->json(['success' => false, 'message' => 'Invalid path components.'], 400);
        }

        $filePath = $type . '/' . $folder . '/' . $fileName;

        try {
            if (!Storage::disk('public')->exists($filePath)) {
                throw new FileNotFoundException("File not found: {$filePath}");
            }
            
            Log::info('File accessed for download/show', [
                'path' => $filePath,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip()
            ]);

            // Use download for direct download, or response()->file() for inline display
            return Storage::disk('public')->download($filePath);
            // For inline display (e.g., images in <img> tag or PDFs in browser):
            // return response()->file(Storage::disk('public')->path($filePath));

        } catch (FileNotFoundException $e) {
            Log::warning('Attempt to access non-existent file for show/download', ['path' => $filePath, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error accessing file for show/download', [
                'path' => $filePath,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Trace hidden in production'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while accessing the file.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error.'
            ], 500);
        }
    }
}
