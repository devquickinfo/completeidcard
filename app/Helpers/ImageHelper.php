<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use App\Models\student;
use App\Models\SelectedSample;
use App\Models\UploadSample;
use App\Models\Mainidcard;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicableUser;
use App\Models\House;
use App\Models\StudentClass;


class ImageHelper
{
    /**
     * Process and save student photo.
     *
     * Supports:
     * - Base64 camera image
     * - UploadedFile
     *
     * Returns:
     * students/{school_id}/{filename}.jpg
     */
    public static function processStudentPhoto(
        $image,
        int|string $schoolId
    ): string {

        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is not enabled.');
        }

        /*
        |--------------------------------------------------------------------------
        | Get binary image data
        |--------------------------------------------------------------------------
        */
        if ($image instanceof UploadedFile) {

            if (!$image->isValid()) {
                throw new \Exception(
                    'Uploaded photo is invalid.'
                );
            }

            $imageData = file_get_contents(
                $image->getRealPath()
            );

        } elseif (is_string($image)) {

            $imageData = self::decodeBase64Image($image);

        } else {

            throw new \Exception(
                'Invalid photo data.'
            );
        }

        if (!$imageData) {
            throw new \Exception(
                'Image data is empty.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create source image
        |--------------------------------------------------------------------------
        */
        $source = @imagecreatefromstring($imageData);

        if (!$source) {
            throw new \Exception(
                'Unable to read image. Please use a valid JPG, PNG or WebP image.'
            );
        }

        $originalWidth = imagesx($source);
        $originalHeight = imagesy($source);

        /*
        |--------------------------------------------------------------------------
        | 4:5 ratio
        |--------------------------------------------------------------------------
        */
        $targetRatio = 4 / 5;

        $originalRatio =
            $originalWidth / $originalHeight;

        if ($originalRatio > $targetRatio) {

            /*
            | Image is wider
            */
            $cropHeight = $originalHeight;

            $cropWidth = (int) round(
                $originalHeight * $targetRatio
            );

            $srcX = (int) (
                ($originalWidth - $cropWidth) / 2
            );

            $srcY = 0;

        } else {

            /*
            | Image is taller
            */
            $cropWidth = $originalWidth;

            $cropHeight = (int) round(
                $originalWidth / $targetRatio
            );

            $srcX = 0;

            $srcY = (int) (
                ($originalHeight - $cropHeight) / 2
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Output size
        |--------------------------------------------------------------------------
        */
        $targetWidth = 472;
        $targetHeight = 591;

        $processed = imagecreatetruecolor(
            $targetWidth,
            $targetHeight
        );

        /*
        |--------------------------------------------------------------------------
        | White background
        |--------------------------------------------------------------------------
        */
        $white = imagecolorallocate(
            $processed,
            255,
            255,
            255
        );

        imagefill(
            $processed,
            0,
            0,
            $white
        );

        /*
        |--------------------------------------------------------------------------
        | Resize / crop
        |--------------------------------------------------------------------------
        */
        imagecopyresampled(
            $processed,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            $targetWidth,
            $targetHeight,
            $cropWidth,
            $cropHeight
        );

        /*
        |--------------------------------------------------------------------------
        | Image enhancement
        |--------------------------------------------------------------------------
        */
        imagefilter(
            $processed,
            IMG_FILTER_BRIGHTNESS,
            8
        );

        imagefilter(
            $processed,
            IMG_FILTER_CONTRAST,
            -5
        );

        /*
        |--------------------------------------------------------------------------
        | Compress
        |--------------------------------------------------------------------------
        */
        $quality = 85;

        $jpegData = null;
        $size = 0;

        while ($quality >= 40) {

            ob_start();

            imagejpeg(
                $processed,
                null,
                $quality
            );

            $jpegData = ob_get_clean();

            $size = strlen($jpegData);

            if ($size <= 1024 * 1024) {
                break;
            }

            $quality -= 5;
        }

        /*
        |--------------------------------------------------------------------------
        | If still bigger than 1MB
        |--------------------------------------------------------------------------
        */
        if ($size > 1024 * 1024) {

            $newWidth = 400;
            $newHeight = 500;

            $smaller = imagecreatetruecolor(
                $newWidth,
                $newHeight
            );

            $white = imagecolorallocate(
                $smaller,
                255,
                255,
                255
            );

            imagefill(
                $smaller,
                0,
                0,
                $white
            );

            imagecopyresampled(
                $smaller,
                $processed,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $targetWidth,
                $targetHeight
            );

            $quality = 75;

            while ($quality >= 40) {

                ob_start();

                imagejpeg(
                    $smaller,
                    null,
                    $quality
                );

                $jpegData = ob_get_clean();

                $size = strlen($jpegData);

                if ($size <= 1024 * 1024) {
                    break;
                }

                $quality -= 5;
            }

            imagedestroy($smaller);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate filename
        |--------------------------------------------------------------------------
        */
        $filename =
            'student_' .
            date('YmdHis') .
            '_' .
            uniqid() .
            '.jpg';

        /*
        |--------------------------------------------------------------------------
        | Storage path
        |--------------------------------------------------------------------------
        */
        $path =
            'students/' .
            $schoolId .
            '/' .
            $filename;

        /*
        |--------------------------------------------------------------------------
        | Save to storage/app/public
        |--------------------------------------------------------------------------
        */
        Storage::disk('public')->put(
            $path,
            $jpegData
        );

        /*
        |--------------------------------------------------------------------------
        | Verify file was actually saved
        |--------------------------------------------------------------------------
        */
        if (!Storage::disk('public')->exists($path)) {

            imagedestroy($source);
            imagedestroy($processed);

            throw new \Exception(
                'Photo could not be saved to storage.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Free memory
        |--------------------------------------------------------------------------
        */
        imagedestroy($source);
        imagedestroy($processed);

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Return the storage path, NOT true/false.
        */
        return $path;
    }


    /**
     * Decode Base64 image.
     */
    private static function decodeBase64Image(
        string $imageData
    ): string {

        $imageData = trim($imageData);

        /*
        |--------------------------------------------------------------------------
        | Remove data:image/...;base64, prefix
        |--------------------------------------------------------------------------
        */
        if (preg_match(
            '/^data:image\/[a-zA-Z0-9.+-]+;base64,(.*)$/s',
            $imageData,
            $matches
        )) {

            $imageData = $matches[1];
        }

        /*
        |--------------------------------------------------------------------------
        | Remove whitespace
        |--------------------------------------------------------------------------
        */
        $imageData = preg_replace(
            '/\s+/',
            '',
            $imageData
        );

        /*
        |--------------------------------------------------------------------------
        | Decode
        |--------------------------------------------------------------------------
        */
        $decoded = base64_decode(
            $imageData,
            true
        );

        if ($decoded === false) {

            throw new \Exception(
                'Invalid Base64 image.'
            );
        }

        return $decoded;
    }


    /**
     * Uploaded file helper.
     */
    public static function processUploadedPhoto(
        UploadedFile $file,
        int|string $schoolId
    ): string {

        return self::processStudentPhoto(
            $file,
            $schoolId
        );
    }

    public static function getSchoolName($id){
        return School::where('id', $id)->value('school_name');
    }
    public function getImageurl($id){
        $imageUrl=Student::where('id',$id)->value('photo');
        return $imageUrl;
    }



    



    
    public static  function saveImageAsJpg(
            string $base64Image,
            string $folder,
            ?string $filePrefix = null
        ): string {
            // Remove base64 prefix
            $base64Image = preg_replace(
                '/^data:image\/\w+;base64,/',
                '',
                $base64Image
            );

            $base64Image = str_replace(' ', '+', $base64Image);

            // Decode image
            $imageData = base64_decode($base64Image, true);

            if ($imageData === false) {
                throw new \Exception('Invalid image data.');
            }

            // Create image
            $sourceImage = imagecreatefromstring($imageData);

            if ($sourceImage === false) {
                throw new \Exception('Unable to process image.');
            }

            // Temporary file
            $tempPath = tempnam(
                sys_get_temp_dir(),
                'image_'
            );

            $maxSize = 1024 * 1024; // 1 MB
            $quality = 90;

            // Compress until <= 1 MB
            do {

                imagejpeg(
                    $sourceImage,
                    $tempPath,
                    $quality
                );

                $fileSize = filesize($tempPath);

                if ($fileSize > $maxSize) {
                    $quality -= 5;
                }

            } while (
                $fileSize > $maxSize &&
                $quality >= 20
            );

            // Still bigger than 1 MB
            if ($fileSize > $maxSize) {

                imagedestroy($sourceImage);
                @unlink($tempPath);

                throw new \Exception(
                    'Unable to compress image below 1 MB.'
                );
            }

            // Filename
            $filePrefix = $filePrefix
                ? $filePrefix . '_'
                : '';

            $fileName = $filePrefix
                . time()
                . '_'
                . uniqid()
                . '.jpg';

            // Path
            $imagePath = trim($folder, '/') . '/' . $fileName;

            // Save
            Storage::disk('public')->put(
                $imagePath,
                file_get_contents($tempPath)
            );

            // Cleanup
            imagedestroy($sourceImage);
            @unlink($tempPath);

            return $imagePath;
        }



    public static function getIdCard($studentId)
    {
        $student = Student::findOrFail($studentId);

        $schoolId = Auth::user()->school_id
            ?? session('viewing_school');

        $school = School::where('id', $schoolId)->first();

        /*
        |--------------------------------------------------------------------------
        | Vertical Sample
        |--------------------------------------------------------------------------
        */

        $verticalSelectedSample = SelectedSample::where(
                'school_id',
                $schoolId
            )
            ->where('orientation', 'vertical')
            ->first();

        $verticalSample = null;

        if ($verticalSelectedSample) {

            $verticalSample = UploadSample::find(
                $verticalSelectedSample->sample_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Horizontal Sample
        |--------------------------------------------------------------------------
        */

        $horizontalSelectedSample = SelectedSample::where(
                'school_id',
                $schoolId
            )
            ->where('orientation', 'horizontal')
            ->first();

        $horizontalSample = null;

        if ($horizontalSelectedSample) {

            $horizontalSample = UploadSample::find(
                $horizontalSelectedSample->sample_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vertical Design
        |--------------------------------------------------------------------------
        */

        $verticalDesign = Mainidcard::where(
                'school_id',
                $schoolId
            )
            ->where('orientation', 'vertical')->where('is_default',1)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Horizontal Design
        |--------------------------------------------------------------------------
        */

        $horizontalDesign = Mainidcard::where(
                'school_id',
                $schoolId
            )
            ->where('orientation', 'horizontal')->where('is_default',1)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [
            'student' => $student,

            'verticalSample' => $verticalSample,

            'horizontalSample' => $horizontalSample,

            'verticalDesign' => $verticalDesign,

            'horizontalDesign' => $horizontalDesign,

            'school' => $school,
        ];
    }
    
    public static function getHouse($id)
    {
        return House::find($id)?->name;
    }
    public static function getApplicableUser($id){
        
        return ApplicableUser::find($id)?->type;
    }
    public static function getClassName($id){

        return StudentClass::find($id)?->name;
    }

    public static function getLayoutStatus($id)
    {
        return Mainidcard::where('sample_id', $id)
            ->value('is_default') ?? 0;
    }
   
  
}