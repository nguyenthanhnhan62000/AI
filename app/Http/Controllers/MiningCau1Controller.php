<?php

namespace App\Http\Controllers;

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


    public function index(){

        $this->docCollection["DocumentList"][] = "HLV Park Hang Seo nói gì sau chiến thắng tưng bừng trước Pakistan?";
        $this->docCollection["DocumentList"][] = "HLV Park Hang-seo 'mất niềm tin', tiết lộ về 2 pha hỏng ăn penalty liên tiếp của Công Phượng";
        $this->docCollection["DocumentList"][] = "Xả súng kinh hoàng tại Điện Biên khiến 2 vợ chồng tử vong";
        $this->docCollection["DocumentList"][] = "Nghi án nổ súng ở Điện Biên, hai vợ chồng tử vong tại chỗ";
        $this->docCollection["DocumentList"][] = "Sập cầu ở Ý, 35 người thiệt mạng";
        $this->docCollection["DocumentList"][] = "Công Phượng đá hỏng 2 quả penalty, bố mẹ ở nhà nghĩ gì?";
        
        $this->ProcessDocumentCollection($this->docCollection);

        $this->PrepareDocumentCluster(2, $this->space);

        
    }
    public function PrepareDocumentCluster($k, $documentCollection){
        $globalCounter = 0;
        $centroidCollection = [];
        $uniqRand = [];

        $uniqRand = $this->GenerateRandomNumber($k,count($documentCollection)-1);


        foreach ($uniqRand as $pos) {
            $centroidCollection["GroupedDocument"][] = $this->docCollection["DocumentList"][$pos];
        }
        dd($centroidCollection);
        

    }
    public function GenerateRandomNumber($k,$docCount){
        $uniqRand = [];
        if ($k > $docCount){

            do {
                $pos = random_int(0,$docCount);
                $uniqRand[] = $pos;
            } while (count($uniqRand) != $docCount);

        }else{
            do {

                $pos = random_int(0,$docCount);
                $uniqRand[] = $pos;
            } while (count($uniqRand) != $k);
        }
        return $uniqRand;
    }
    public function ProcessDocumentCollection($collection){
        
        foreach($collection["DocumentList"] as $documentContent){
            $it = explode(" ", $documentContent);
            foreach ($it as $value) {
                $this->distinctTerms[] = $value;
            }
        }
        $this->distinctTerms = array_unique($this->distinctTerms);
        foreach ($collection["DocumentList"] as $k =>  $document) {
            foreach ($this->distinctTerms as $key => $term) {
                $this->space[$k][] = $this->FindTFIDF($document, $term);
            }
        }
        // dd($this->space);
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
    
}
