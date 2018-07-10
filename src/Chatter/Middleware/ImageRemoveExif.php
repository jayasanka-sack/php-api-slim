<?php

namespace Chatter\Middleware;

class ImageRemoveExif
{
    public function __invoke($request, $response, $next)
    {
        $files = $request->getUploadedFiles();
        $newfile = $files['file'];
        $newfile_type = $newfile->getClientMediaType();
        $uploadFileName = $newfile->getClientFilename();
        $newfile->moveTo("assets/images/raw/$uploadFileName");
        $pngfile = "assets/images/" . substr($uploadFileName, 0, -4) . ".png";

        if ('image/jpeg' == $newfile_type) {
            $_img = imagecreatefromjpeg("assets/images/raw/$uploadFileName");
            imagepng($_img, $pngfile);
        }

        $request = $request->withAttribute('png_filename', $pngfile);
        $response = $next($request, $response);

        return $response;
    }
}