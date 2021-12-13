<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/Bat-dong-san/Luat-Dat-dai-2003-13-2003-QH11-51685.aspx';

        $page = $client->request('GET', $url);

        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it,'Chương') || Str::contains($it,'CHƯƠNG')) && (strpos($it,'Chương') === 0 || strpos($it,'CHƯƠNG') === 0 )) {
                $this->chapter = $it;
                $this->child = '';
            }else if(Str::contains($it,'Điều') && strpos($it,'Điều') === 0){
                $this->child = $it;
            }else if($this->chapter !== '' && $this->child !== ''){
                $this->results[$this->chapter][$this->child][] = $it;
            }
        });
        dd($this->results);

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
    public function show($id)
    {
        //
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
