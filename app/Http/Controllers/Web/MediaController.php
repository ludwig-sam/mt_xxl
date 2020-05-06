<?php

namespace App\Http\Controllers\Web;



use Illuminate\Http\Request;

class MediaController
{

    public function rule()
    {
    }

    public function get(Request $request)
    {
        $url  = $request->query('mp');

        $content = file_get_contents($url);

        return response($content)->header('Content-Type', 'image/jpeg');
    }

}
