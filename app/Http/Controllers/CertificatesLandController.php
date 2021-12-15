<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificates_land;

class CertificatesLandController extends Controller
{
    public $data;
    public $keyword;
    public function search(Request $request){

        
        if (isset($request->keyword)) {
            $this->keyword = $request->keyword;
            $this->data = Certificates_land::
                where('name','like','%' . $request->keyword . '%')  
                ->paginate(10);
            $this->data->appends($request->all());
        }else{
            $this->data = Certificates_land::paginate(10);

        }
        return view('cau_3.search', ['data' => $this->data]);
    }
}
