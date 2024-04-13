<?php

namespace App\Http;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new Oa\Info(
        version: 'v1',
        description: 'A Chess backend api for playing games of chess.',
        title: 'Chess-backend'
    )
)]
class OpenApi
{
}
