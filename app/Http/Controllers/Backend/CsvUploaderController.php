<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;

use App\Claim;



class CsvUploaderController extends BackendController
{

    public function getIndex()
    {
        return backend_view('csv-uploader.index3');
    }

}
