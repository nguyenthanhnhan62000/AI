<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;


class LandPriceController extends Controller
{
    private $results = [];
    private $index = 0;

    public function search(){
        $data = $this->search_gen();
        return view('landprice/search', compact('data'));
    }
    public function search_post(Request $request){

        
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

        $data = $this->results;
        return $data;
    }
}
