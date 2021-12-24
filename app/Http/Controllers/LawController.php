<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Mining;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
    public $index;
    public function index()
    {
        // $it = 'Điều 1. Điều 126 của Luật nhà ở được sửa đổi, bổ sung như sau:';
        // dd(stripos($it, 'Điều'));
        return view('law.index');
    }
    public function post_index(Request $request)
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/phap-luat/tim-van-ban.aspx?keyword=' . $request->textSearch . '&area=0&type=0&status=0&lan=1&org=0&signer=0&match=True&sort=1&bdate=13/12/1941&edate=14/12/2021&page=' . $request->page;
        $page = $client->request('GET', $url);
        $page->filter('.content-0')->each(function ($item) {
            $this->index = $item->filter('.number')->text();
            $href = $item->filter('a')->attr('href');
            $href_new = str_replace("https://thuvienphapluat.vn/", "", $href);
            $this->results[$this->index][] = $item->filter('a')->html();
            $this->results[$this->index][] = $href_new;
            $this->results[$this->index][] = $item->filter('.nqContent')->html();
            $item->filter('.right-col')->each(function ($it) {
                $it->filter('p')->each(function ($i) {
                    $this->results[$this->index][] = $i->text();
                });
            });
        });
        $page->filter('.content-1')->each(function ($item) {
            $this->index = $item->filter('.number')->text();
            $href = $item->filter('a')->attr('href');
            $href_new = str_replace("https://thuvienphapluat.vn/", "", $href);
            $this->results[$this->index][] = $item->filter('a')->html();
            $this->results[$this->index][] = $href_new;
            $this->results[$this->index][] = $item->filter('.nqContent')->html();
            $item->filter('.right-col')->each(function ($it) {
                $it->filter('p')->each(function ($i) {
                    $this->results[$this->index][] = $i->text();
                });
            });
        });
        $data = $this->results;

        return response()->json(['data' => $data]);
    }

    public function crawl_content($path)
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/' . $path;
        $page = $client->request('GET', $url);
        $content = $page->filter('.content1')->html();
        return $content;
    }
    public function post_search(Request $request)
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/' . $request->path;
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it, 'Chương') || Str::contains($it, 'CHƯƠNG')) && (strpos($it, 'Chương') === 0 || strpos($it, 'CHƯƠNG') === 0)) {
                $this->chapter = $it;
                $this->child = '';
            } else if ((Str::contains($it, 'Điều') && strpos($it, 'Điều') === 0) || (Str::contains($it, "“Điều"))) {
                $this->child = $it;
            } else if ($this->chapter !== '' && $this->child !== '') {
                $this->results[$this->chapter][$this->child][] = $it;
            } else if ($this->chapter === '' && $this->child !== '') {
                $this->results[$this->child][] = $it;
            }
        });
        return response()->json(['data' => $this->results]);
    }

    public function show()
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/Bo-may-hanh-chinh/Nghi-dinh-148-2020-ND-CP-sua-doi-mot-so-Nghi-dinh-huong-dan-Luat-Dat-dai-427504.aspx';
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if (stripos($it, 'Chương') === 0 || stripos($it, 'CHƯƠNG') === 0) {
                $this->chapter = $it;
                $this->child = '';
            } else if (stripos($it, 'Điều') === 0) {
                $this->child = $it;
            } else if ($this->chapter !== '' && $this->child !== '') {
                $this->results[$this->chapter][$this->child][] = $it;
            } else if ($this->chapter === '' && $this->child !== '') {
                $this->results[$this->child][] = $it;
            }
        });
        $data = json_encode($this->results);

        $content1 = $this->crawl_content('');
        return view('law.view', compact('data', 'content1'));
    }

    public function post_show(Request $request)
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/' . $request->path;
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it, 'Chương') || Str::contains($it, 'CHƯƠNG')) && (strpos($it, 'Chương') === 0 || strpos($it, 'CHƯƠNG') === 0)) {
                $this->chapter = $it;
                $this->child = '';
            } else if ((Str::contains($it, 'Điều') && strpos($it, 'Điều') === 0) || (Str::contains($it, "“Điều"))) {
                $this->child = $it;
            } else if ($this->chapter !== '' && $this->child !== '') {
                $this->results[$this->chapter][$this->child][] = $it;
            } else if ($this->chapter === '' && $this->child !== '') {
                $this->results[$this->child][] = $it;
            }
        });
        $data = json_encode($this->results);
        $content1 = $this->crawl_content($request->path);
        return view('law.view', compact('data', 'content1'));
    }

    public function sendToDataMining()
    {
        $client = new Client();
        $url = 'https://thuvienphapluat.vn/van-ban/Bat-dong-san/Luat-dat-dai-2013-215836.aspx';
        $page = $client->request('GET', $url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it, 'Chương') || Str::contains($it, 'CHƯƠNG')) && (strpos($it, 'Chương') === 0 || strpos($it, 'CHƯƠNG') === 0)) {
                $this->chapter = $it;
                $this->child = '';
            } else if ((Str::contains($it, 'Điều') && strpos($it, 'Điều') === 0) || (Str::contains($it, "“Điều"))) {
                $this->child = $it;
            } else if ($this->chapter !== '' && $this->child !== '') {
                $this->results[$this->chapter][$this->child][] = $it;
            } else if ($this->chapter === '' && $this->child !== '') {
                $this->results[$this->child][] = $it;
            }
        });

        // $data = json_encode($this->results);
        $data = $this->results;

        $dataNew = [];

        foreach ($data as $key => $items) {

            foreach ($items as $key_ => $item) {

                foreach ($item as $key__ => $it) {

                    $arr = array("chuong" => $key, "dieu" => $key_, "nd" => $it);
                    $obj = (object)$arr;
                    $dataNew[] = $obj;
                }
            }
        }
        return $dataNew;
    }

    public function post_test(Request $request)
    {

        $client = new Client();
        $page = $client->request('GET', $request->url);
        $page->filter('p')->each(function ($item) {
            $it = $item->text();
            if ((Str::contains($it, 'Chương') || Str::contains($it, 'CHƯƠNG')) && (strpos($it, 'Chương') === 0 || strpos($it, 'CHƯƠNG') === 0)) {
                $this->chapter = $it;
                $this->child = '';
            } else if ((Str::contains($it, 'Điều') && strpos($it, 'Điều') === 0) || (Str::contains($it, "“Điều"))) {
                $this->child = $it;
            } else if ($this->chapter !== '' && $this->child !== '') {
                $this->results[$this->chapter][$this->child][] = $it;
            } else if ($this->chapter === '' && $this->child !== '') {
                $this->results[$this->child][] = $it;
            }
        });
        $data = $this->results;

        $dataNew = [];

        foreach ($data as $key => $items) {

            foreach ($items as $key_ => $item) {

                foreach ($item as $key__ => $it) {

                    $arr = array("chuong" => $key, "dieu" => $key_, "nd" => $it);
                    $obj = (object)$arr;
                    $dataNew[] = $obj;
                }
            }
        }
        return $dataNew;
    }


    //data mining 
    public $array;
    public $arrayInput;
    public function test()
    {

        // $string1 = "HLV Park Hang Seo nói gì sau chiến thắng tưng bừng trước Pakistan?";
        // $string2 = "HLV Park Hang-seo 'mất niềm tin', tiết lộ về 2 pha hỏng ăn penalty liên tiếp của Công Phượng";
        // $string3 = "Xả súng kinh hoàng tại Điện Biên khiến 2 vợ chồng tử vong" ;
        // $string5 = "Nghi án nổ súng ở Điện Biên, hai vợ chồng tử vong tại chỗ" ;
        // $string6 = "Sập cầu ở Ý, 35 người thiệt mạng" ;
        // $string7 = "Công Phượng đá hỏng 2 quả penalty, bố mẹ ở nhà nghĩ gì?";
        $string4 = "Ông Cao Hữu Hiếu, Tổng giám đốc Vinatex cho biết, năm nay có sự phân hoá giữa mức thưởng của doanh nghiệp dệt may phía Nam, Bắc và Trung trong tập đoàn.";

        $this->arrayInput = $this->guess_mining();
        // dd($this->arrayInput);
        // foreach ($data as $key => $value) {
        //     $this->arrayInput[] = $value;
        // }
        // dd($this->arrayInput);
        // dd($data);


        // dd($this->arrayInput);
        // $this->arrayInput = [$string1, $string2, $string3,$string5,$string6,$string7];
        // dd($this->arrayInput);
        $arrText = [];
        $space = [];
        $space_guess = [];
        // all text
        foreach ($this->arrayInput as $key => $item) {
            $it = explode(" ", $item);
            foreach ($it as $value) {
                $arrText[] = $value;
            }
        }
        $this->array = array_unique($arrText);
        // dd($array);

        foreach ($this->arrayInput as $items) {
            foreach ($this->array as $key => $item) {
                $space[$items][] = $this->FindTFIDF($items, $item);
            }
        }

        foreach ($this->array as $key => $item) {
            $space_guess[$string4][] = $this->FindTFIDF($string4, $item);
        }


        $index = $this->FindClosestClusterCenter($space, $space_guess[$string4]);
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
        dd($similarityMeasure);
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


    public $item;
    public $text;
    public $href;
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
    public $url;

    public function guess_mining()
    {
        $data = Mining::all();
        foreach ($data as $key => $result) {

            $this->text = "";
            $this->url = $result->url;
            $client = new Client();
            $page = $client->request('GET', $this->url);

            $page->filter('.col-left-top h3 a')->each(function ($item) {


                $_url = $item->attr('href');
                $client = new Client();
                $_page = $client->request('GET', $_url);
                $this->text = $this->text. $_page->filter('.fck_detail')->text();
                // echo '<br>';
                
            });
            $this->results[] = $this->text;
            // $this->results[] = mb_substr($this->text, 0,500,'UTF-8');
        }
        // dd($this->results);
        return $this->results;
    }
}
