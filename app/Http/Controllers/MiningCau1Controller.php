<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MiningCau1Controller extends Controller
{

    public $array;
    public $arrayInput;
    public $results = [];
    public $index;
    public $item;
    public $text;
    public $href;
    public $url;
    public $distinctTerms = [];
    public $space = [];
    public $space_guess = [];
    public $docCollection = [];
    public $chapter = '';
    public $child = '';


    public function index()
    {
        // $data = $this->getDataFromSourceWeb();
        // for ($i=0; $i < 100; $i++) { 
        //     $this->docCollection["DocumentList"][] = $data[$i]->nd;
        // }
        // // dd($this->docCollection["DocumentList"]);
        // $this->ProcessDocumentCollection($this->docCollection);
        // $result = $this->PrepareDocumentCluster(5, $this->space);
        // dd($result);
        // return view('mining.cau1_index',["data" => json_encode($data)]);
        return view('mining.cau1_index');
    }

    public function search(Request $request)
    {

        $array = $request->array;
        $this->docCollection = $request->docCollection;
        // $array = (array)$array;
        $space = $request->space;
        foreach ($array as  $item) {
            $this->space_guess[0][] = $this->FindTFIDF($request->guess_test, $item);
        }
        $arr = $this->_FindClosestClusterCenter($space, $this->space_guess[0]);

        arsort($arr);
        $arr_num = [];
        foreach ($arr as $key => $value) {
            $arr_num[] = [$key,$value];
        }
        return json_encode($arr_num);
    }

    public function data_post(Request $request)
    {

        $data = $this->getDataFromSourceWeb($request->url);
        return json_encode($data);
    }
    public function cluster_post(Request $request)
    {

        for ($i = 0; $i < 20; $i++) {

            $this->docCollection["DocumentList"][] = $request->docCollection[$i]['nd'];
        }
        $this->ProcessDocumentCollection($this->docCollection);

        $result = $this->PrepareDocumentCluster($request->cluster, $this->space);

        return json_encode(["result" => $result, "space" => $this->space, "array" => $this->distinctTerms, "docCollection" => $this->docCollection]);
        // return json_encode($result);

    }
    public $accept;
    public function getDataFromSourceWeb($url)
    {
        $client = new Client();
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
        $data = $this->results;

        $dataNew = [];
        $this->accept = 0;
        foreach ($this->results as $key => $value) {
            if ((Str::contains($key, 'Chương') || Str::contains($key, 'CHƯƠNG'))) {
                $this->accept = 1;
                break;
            }
            break;
        }

        if ($this->accept == 1) {
            foreach ($data as $key => $items) {
                foreach ($items as $key_ => $item) {
                    foreach ($item as $key__ => $it) {
                        $arr = array("chuong" => $key, "dieu" => $key_, "nd" => $it);
                        $obj = (object)$arr;
                        $dataNew[] = $obj;
                    }
                }
            }
        } else {
            foreach ($data as $key => $items) {
                foreach ($items as $key_ => $item) {
                    $arr = array("dieu" => $key, "nd" => $item);
                    $obj = (object)$arr;
                    $dataNew[] = $obj;
                }
            }
        }

        return $dataNew;
    }
    public function PrepareDocumentCluster($k, $documentCollection)
    {

        $globalCounter = 0;
        $centroidCollection = [];
        $uniqRand = [];
        $stoppingCriteria = true;
        $resultSet = [];
        $prevClusterCenter = [];

        $uniqRand = $this->GenerateRandomNumber($k, count($documentCollection));

        foreach ($uniqRand as $k => $pos) {
            $centroidCollection[$k]["GroupedDocument"][] = $this->space[$pos];
        }

        do {
            $prevClusterCenter = $centroidCollection;

            // dd($this->docCollection);

            foreach ($this->space as $obj) {
                $index = $this->FindClosestClusterCenter($centroidCollection, $obj);
                // echo (string)$index . '<br>';
                $resultSet[$index]['GroupedDocument'][] = $obj;
            }

            $centroidCollection = [];

            $centroidCollection = $this->CalculateMeanPoints($resultSet);

            $stoppingCriteria = $this->CheckStoppingCriteria($prevClusterCenter, $centroidCollection);

            if (!$stoppingCriteria) {
                $resultSet = [];
            }
        } while ($stoppingCriteria == false);
        return $resultSet;
    }
    public $globalCounter = 0;
    public function CheckStoppingCriteria($prevClusterCenter, $newClusterCenter)
    {
        $this->globalCounter++;
        $counter = $this->globalCounter;
        if ($this->globalCounter > 11000) {
            return true;
        } else {

            $stoppingCriteria = true;
            $changeIndex = [];
            $index = 0;
            do {
                $count = 0;

                if (count($newClusterCenter[$index]["GroupedDocument"]) == 0 && count($prevClusterCenter[$index]["GroupedDocument"]) == 0) {

                    $index++;
                } else if (count($newClusterCenter[$index]["GroupedDocument"]) != 0 && count($prevClusterCenter[$index]["GroupedDocument"]) != 0) {

                    for ($j = 0; $j < count($newClusterCenter[$index]["GroupedDocument"][0][1]); $j++) {

                        if ($newClusterCenter[$index]["GroupedDocument"][0][1][$j] == $prevClusterCenter[$index]["GroupedDocument"][0][1][$j]); {
                            $count++;
                        }
                    }
                    if ($count == count($newClusterCenter[$index]["GroupedDocument"][0][1])) {
                        $changeIndex[$index] = 0;
                    } else {
                        $changeIndex[$index] = 1;
                    }
                    $index++;
                } else {
                    $index++;
                    continue;
                }
            } while ($index < count($newClusterCenter));
            // dd($changeIndex);
            foreach ($changeIndex as $item) {

                if ($item != 0) {
                    $stoppingCriteria = false;
                    return $stoppingCriteria;
                } else {
                    $stoppingCriteria = true;
                }
            }
            return $stoppingCriteria;
        }
    }
    public function CalculateMeanPoints($_clusterCenter)
    {

        for ($i = 0; $i < count($_clusterCenter); $i++) {

            if (count($_clusterCenter[$i]["GroupedDocument"]) > 0) {
                for ($j = 0; $j < count($_clusterCenter[$i]["GroupedDocument"][0][1]); $j++) {
                    $total = 0;

                    foreach ($_clusterCenter[$i]["GroupedDocument"] as $vSpace) {

                        $total += (float)$vSpace[1][$j];
                    }
                    $_clusterCenter[$i]["GroupedDocument"][0][1][$j] = $total / count($_clusterCenter[$i]["GroupedDocument"]);
                }
            }
        }
        return $_clusterCenter;
    }

    public function GenerateRandomNumber($k, $docCount)
    {
        $uniqRand = [];
        if ($k > $docCount) {

            do {
                $pos = random_int(0, $docCount);
                $uniqRand[] = $pos;
            } while (count($uniqRand) != $docCount);
        } else {
            do {

                $pos = random_int(0, $docCount);
                $uniqRand[] = $pos;
            } while (count($uniqRand) != $k);
        }

        return $uniqRand;
    }
    public function ProcessDocumentCollection($collection)
    {

        foreach ($collection["DocumentList"] as $documentContent) {
            $it = explode(" ", $documentContent);
            foreach ($it as $value) {
                $this->distinctTerms[] = $value;
            }
        }
        $this->distinctTerms = array_unique($this->distinctTerms);
        foreach ($collection["DocumentList"] as $k =>  $document) {

            $this->array = [];
            foreach ($this->distinctTerms as $key => $term) {
                $this->array[] = $this->FindTFIDF($document, $term);
            }
            $this->space[$k] = [$this->docCollection["DocumentList"][$k], $this->array];
        }
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
        foreach ($this->docCollection["DocumentList"] as $item) {
            // echo strtoupper($item).'---'.strtoupper($term) .'--'.Str::contains(strtoupper($item),strtoupper($term)). '<br>';

            if (Str::contains(strtoupper($item), strtoupper($term))) {
                $count++;
            }
        }
        return (float)log((float)$count / (1 + (float)count($this->docCollection["DocumentList"])));
    }
    public function FindClosestClusterCenter($clusterCenter, $obj)
    {
        $similarityMeasure = [];
        foreach ($clusterCenter as $key =>  $item) {
            $similarityMeasure[] = $this->FindCosineSimilarity($item["GroupedDocument"][0][1], $obj[1]);
        }
        $index = 0;
        $maxValue = $similarityMeasure[0];
        for ($i = 0; $i < count($similarityMeasure); $i++) {
            //if document is similar assign the document to the lowest index cluster center to avoid the long loop
            if ($similarityMeasure[$i] > $maxValue) {
                $maxValue = $similarityMeasure[$i];
                $index = $i;
            }
        }
        return $index;
    }
    public function _FindClosestClusterCenter($clusterCenter, $obj)
    {
        $similarityMeasure = [];
        foreach ($clusterCenter as $key =>  $item) {

            $similarityMeasure[] = $this->FindCosineSimilarity($item[1], $obj);
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
}
