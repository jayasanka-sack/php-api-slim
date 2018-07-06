<?php

namespace Chatter\Middleware;


class ImageRemoveExif
{
    public function __invoke($request, $response, $next)
    {
        $files = $request->getUploadedFiles();
        $newfile = $files['file'];
        $newfile_type = $newfile->getClientMediaType();
        $uploadedFilename = $newfile->getClientFilename();
        $newfile->moveTo("assets/images/raw/$uploadedFilename");
        $pngfile = "assets/images/" . substr($uploadedFilename, 0, -4) . ".png";

        if ('image/jpeg' == $newfile_type) {
            $_img = imagecreatefromjpeg("assets/images/raw/" . $uploadedFilename);
            imagepng($_img, $pngfile);
        }


        $response = $next($request, $response);

        return $response;
    }
}