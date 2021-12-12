<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;


class LandPriceController extends Controller
{
    private $results = [];
    private $index = 1;
    public $amount = 594640;
    public function search(){

        return view('landprice/search');
    }
    public function search_post(Request $request){

        $data = $this->search_gen($request->TT,$request->QH,$request->TD,$request->MG,$request->p);
        return response()->json(['data' => $data, 'amount' => $this->amount]);

    }
    public function search_gen($slTT = 0, $slQH = 0, $slTD = 0, $slMG = '0-99999', $p = 1){
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/page/BangGiaDat.aspx?city='.$slTT.'&district='.$slQH.'&street='.$slTD.'&pricerange='.$slMG.'&P='.$p;
        $page = $client->request('GET', $url);

        $page->filter('tr[align="center"]')->each(function ($item) {
            $item->filter('td')->each(function ($i){

                $this->results[$this->index][] = $i->text();
            });
            $this->index++;
        });

        $amount = $page->filter('b')->text();

        $this->amount = $amount;
        $data = $this->results;
        return $data;
       
    }
}
