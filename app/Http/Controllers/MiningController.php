<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Mining;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MiningController extends Controller
{
    //data mining 
    public $array;
    public $arrayInput;
    public $results = [];
    public $index;
    public $item;
    public $text;
    public $href;
    public $url;
    public $arrText = [];
    public $space = [];
    public $space_guess = [];

    public function __construct(){
        $this->guess_mining();
        foreach ($this->arrayInput as $key => $item) {
            $it = explode(" ", $item);
            foreach ($it as $value) {
                $this->arrText[] = $value;
            }
        }
        $this->array = array_unique($this->arrText);
        // dd($this->array);
        foreach ($this->arrayInput as $k =>  $items) {
            foreach ($this->array as $key => $item) {
                $this->space[$k][] = $this->FindTFIDF($items, $item);
            }
        }

    }
    public function post_test(Request $request){

        foreach ($this->array as $key => $item) {
            $this->space_guess[0][] = $this->FindTFIDF($request->guess_test, $item);
        }
        $arr = $this->FindClosestClusterCenter($this->space, $this->space_guess[0]);

        return json_encode($arr);
    }
    public function index(){
        // return view('mining.index');

        
        return view('mining.index',["data" => $this->arrayInput,'space'=>$this->space]);
    }
    public function test()
    {

        // $string1 = "HLV Park Hang Seo nói gì sau chiến thắng tưng bừng trước Pakistan?";
        // $string2 = "HLV Park Hang-seo 'mất niềm tin', tiết lộ về 2 pha hỏng ăn penalty liên tiếp của Công Phượng";
        // $string3 = "Xả súng kinh hoàng tại Điện Biên khiến 2 vợ chồng tử vong" ;
        // $string5 = "Nghi án nổ súng ở Điện Biên, hai vợ chồng tử vong tại chỗ" ;
        // $string6 = "Sập cầu ở Ý, 35 người thiệt mạng" ;
        // $string7 = "Công Phượng đá hỏng 2 quả penalty, bố mẹ ở nhà nghĩ gì?";
        $string4 = "Ông Cao Hữu Hiếu, Tổng giám đốc Vinatex cho biết, năm nay có sự phân hoá giữa mức thưởng của doanh nghiệp dệt may phía Nam, Bắc và Trung trong tập đoàn.";
        // dd($this->arrayInput);
        // foreach ($data as $key => $value) {
        //     $this->arrayInput[] = $value;
        // }
        // dd($this->arrayInput);
        // dd($data);
        // dd($this->arrayInput);
        // $this->arrayInput = [$string1, $string2, $string3,$string5,$string6,$string7];
        // dd($this->arrayInput);
        // all text

        foreach ($this->array as $key => $item) {
            $this->space_guess[$string4][] = $this->FindTFIDF($string4, $item);
        }
        $index = $this->FindClosestClusterCenter($this->space, $this->space_guess[$string4]);


    }
    public function FindTFIDF($document, $term)
    {

        $tf = $this->FindTermFrequency($document, $term);
        $idf = $this->FindInverseDocumentFrequency($term);

        return $tf * $idf;
    }
    public function FindTermFrequency($document, $term)
    {

        $arr = explode(" ", $document);
        $count = 0;
        foreach ($arr as $item) {
            if (Str::contains(strtoupper($term), strtoupper($item))) {
                $count++;
            }
        }
        return (float)$count / (count($arr) + count($arr) - 1);
    }
    public function FindInverseDocumentFrequency($term)
    {
        $count = 0;
        foreach ($this->arrayInput as $item) {
            // echo strtoupper($item).'---'.strtoupper($term) .'--'.Str::contains(strtoupper($item),strtoupper($term)). '<br>';

            if (Str::contains(strtoupper($item), strtoupper($term))) {
                $count++;
            }
        }
        return (float)log((float)$count / (1 + (float)count($this->arrayInput)));
    }
    public function FindClosestClusterCenter($cluster, $obj)
    {
        $similarityMeasure = [];
        foreach ($cluster as $key =>  $item) {

            $similarityMeasure[] = $this->FindCosineSimilarity($item, $obj);
        }

        return $similarityMeasure;

    }
    public function FindCosineSimilarity($vecA, $vecB)
    {
        $dotProduct = $this->DotProduct($vecA, $vecB);
        $magnitudeOfA = $this->Magnitude($vecA);
        $magnitudeOfB = $this->Magnitude($vecB);
        $result = 0;
        if ($magnitudeOfA * $magnitudeOfB != 0) {
            $result = $dotProduct / ($magnitudeOfA * $magnitudeOfB);
        }
        return (float)$result;
    }

    public function DotProduct($vecA, $vecB)
    {
        // dd($vecA, $vecB);
        $dotProduct = 0;
        for ($i = 0; $i < count($vecA); $i++) {
            $dotProduct += ($vecA[$i] * $vecB[$i]);
        }

        return $dotProduct;
    }
    public function Magnitude($vector)
    {

        return (float)Sqrt($this->DotProduct($vector, $vector));
    }


  
    public function test_data_mining()
    {

        $client = new Client();
        $url = 'https://vnexpress.net/';
        $pageOra = $client->request('GET', $url);
        $this->index = 0;
        $pageOra->filter('.parent li a')->each(function ($item) {
            if (
                $item->text() != ""
                && $item->text() != "Video"
                && $item->text() != "Tất cả"
                && $item->text() != "Mới nhất"
                && $item->text() != "Podcasts"
            ) {
                $_url = 'https://vnexpress.net' . $item->attr('href');
                $client = new Client();
                $page = $client->request('GET', $_url);
                $this->text = $item->text();
                $this->href = $item->attr('href');
                $page->filter('.ul-nav-folder a')->each(function ($it) {


                    $this->results[] = [$this->text, $this->href, $it->text(), $it->attr('href')];
                });
                $this->index++;
            }
        });

        // dd($this->results);
        foreach ($this->results as $index => $result) {
            $url_ = 'https://vnexpress.net' . $result[3];
            // Mining::create([
            //     "url" => $url_
            // ]);
            echo $url_ . '<br>';
        }
    }


    public function guess_mining()
    {
        $data = Mining::all();
        foreach ($data as $key => $result) {

            $this->text = "";
            $this->url = $result->url;
            $client = new Client();
            $page = $client->request('GET', $this->url);
            $this->index = 0;
            $page->filter('.col-left-top h3 a')->each(function ($item) {


                $_url = $item->attr('href');
                $client = new Client();
                $_page = $client->request('GET', $_url);
                if ($this->index < 2) {
                    $this->text = $this->text . $_page->filter('.fck_detail')->text();
                }
                $this->index++;
                // echo '<br>';

            });
            $this->arrayInput[] = $this->text;
            // $this->arrayInput[] = mb_substr($this->text, 0,1000,'UTF-8');
        }
        // dd($this->arrayInput);
    }
}
