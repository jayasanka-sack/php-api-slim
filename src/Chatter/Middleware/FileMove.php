<?php

namespace Chatter\Middleware;

use Aws\S3\S3Client;

class FileMove
{

    public function __invoke($request, $response, $next)
    {
        $bucketName = 'jayasanka';
        $IAM_KEY = 'AKIAJRHVV4H3ETUTEXDA';
        $IAM_SECRET = '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';


        $s3 = new S3Client([
            'credentials' => [
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ],
            'version' => 'latest',
            'region'  => 'ap-south-1']);

        $files = $request->getUploadedFiles();
        $newfile = $files['file'];
        $uploadFileName = $newfile->getClientFilename();
        $png = "assets/images/" . substr($uploadFileName, 0, -4) . ".png";

        try {
            $s3->putObject([
                'Bucket' => $bucketName,
                'Key'    => 'my-object',
                'Body'   => fopen($png, 'w'),
                'ACL'    => 'public-read',
            ]);
        } catch (Exception $e) {
            return $response->withStatus(400);
        }

        $response = $next($request, $response);

        return $response;
    }
}