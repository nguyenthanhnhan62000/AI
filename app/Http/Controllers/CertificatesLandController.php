<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificates_land;

class CertificatesLandController extends Controller
{
    public $data;
    public function search(Request $request){

        $this->data = Certificates_land::paginate(10);

        if (isset($request->keyword)) {
            $this->data = Certificates_land::where('name','like','%' . $request->keyword . '%')->paginate(10);

        }
        $index = 1;
        return view('cau_3.search', ['data' => $this->data]);
    }
}
