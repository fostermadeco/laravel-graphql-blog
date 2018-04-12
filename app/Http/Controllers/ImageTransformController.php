<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Storage;

class ImageTransformController extends Controller
{
    public function fit($width, $height, $filename)
    {	
    	try {
            
		    return Image::make(Storage::disk('public')->path("images/$filename"))->fit($width, $height)->response('jpg');

    	} catch (\Exception $e){

    	}

    	abort(404);
    }
}
