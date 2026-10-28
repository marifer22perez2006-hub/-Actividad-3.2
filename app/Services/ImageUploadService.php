<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Sube un archivo de imagen a una API externa de almacenamiento de imágenes.
     *
     * @param UploadedFile $file
     * @param string|null $identifier Nombre identificable para localizar la imagen en el servicio.
     * @return string|null Retorna la URL pública proporcionada por la API externa o null si falla.
     */
    public static function upload(UploadedFile $file, ?string $identifier = null): ?string
    {
        try {
            $apiKey = config('services.freeimage.key');

            if (empty($apiKey)) {
                Log::error('No está configurada la clave API de FreeImage.host.');
                return null;
            }

            $imageContents = file_get_contents($file->getRealPath());
            if ($imageContents === false) {
                Log::error('No se pudo leer el archivo de imagen para subirlo a FreeImage.host.');
                return null;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $uploadName = $identifier
                ? preg_replace('/[^A-Za-z0-9_-]/', '-', $identifier) . '.' . $extension
                : basename($file->getClientOriginalName());

            $response = Http::asMultipart()
                ->timeout(20)
                ->attach('source', $imageContents, $uploadName)
                ->post('https://freeimage.host/api/1/upload', [
                    'key' => $apiKey,
                    'action' => 'upload',
                    'format' => 'json'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['image']['url'])) {
                    return $data['image']['url'];
                }
            }

            Log::error('Falló la subida de la imagen a FreeImage.host.', [
                'status' => $response->status(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Excepción al comunicarse con FreeImage.host: ' . $e->getMessage());
            return null;
        }
    }
}
