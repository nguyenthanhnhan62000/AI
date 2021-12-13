<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\law;

class LawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $results = [];
    public $chapter = '';
    public $child = '';

    public function index()
    {
        $laws = Law::all();

        return view('law.index', compact('laws'));
    }
    public function post_search(Request $request){

        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/'. $request->path;
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it,'Chương') || Str::contains($it,'CHƯƠNG')) && (strpos($it,'Chương') === 0 || strpos($it,'CHƯƠNG') === 0 )) {
                $this->chapter = $it;
                $this->child = '';
            }else if((Str::contains($it,'Điều') && strpos($it,'Điều') === 0 ) || (Str::contains($it,"“Điều"))  ){
                $this->child = $it;

            }else if($this->chapter !== '' && $this->child !== ''){
                $this->results[$this->chapter][$this->child][] = $it;

            }else if($this->chapter === '' && $this->child !== ''){
                $this->results[$this->child][] = $it;  
            }
        });
        return response()->json(['data' =>$this->results]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(law $law)
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/Bo-may-hanh-chinh/Nghi-dinh-148-2020-ND-CP-sua-doi-mot-so-Nghi-dinh-huong-dan-Luat-Dat-dai-427504.aspx';
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it,'Chương') || Str::contains($it,'CHƯƠNG')) && (strpos($it,'Chương') === 0 || strpos($it,'CHƯƠNG') === 0 )) {
                $this->chapter = $it;
                $this->child = '';
            }else if((Str::contains($it,'Điều') && strpos($it,'Điều') === 0 ) || (Str::contains($it,"“Điều"))  ){
                $this->child = $it;

            }else if($this->chapter !== '' && $this->child !== ''){
                $this->results[$this->chapter][$this->child][] = $it;

            }else if($this->chapter === '' && $this->child !== ''){
                $this->results[$this->child][] = $it;  
            }
        });
        $data = json_encode($this->results);
        return view('law.view', compact('law','data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

  
}
