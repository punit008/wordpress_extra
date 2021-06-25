<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

class IssuApi
{

    /**
     * @var string
     */
    protected $apiUrl = 'http://api.issuu.com/1_0?';

    /**
     * @var string
     */
    protected $format = 'json';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * IssuApi constructor.
     * @param string $apiKey
     * @param string $secretKey
     */
    function __construct(
        string $apiKey    = 'hy4s6y9189gwmc6q6b2fsvsu7xcgssao',
        string $secretKey = '369eahm3btz3o3dnulbfxlc71dixd7fs'
    ) {
        $this->apiKey    = $apiKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param $request
     * @return string
     */
    protected function sendCurl($request): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function prepareRequest(array $params): string
    {
        $parameters = array_merge([
            'apiKey' => $this->apiKey,
            'format' => $this->format,
        ], $params);

        ksort($parameters);

        $signature = '';
        foreach ($parameters as $key => $value) {
            $signature .= $key;
            $signature .= $value;
        }

        $signature = md5($this->secretKey . $signature);

        $parameters['signature'] = $signature;

        return http_build_query($parameters);
    }

    /**
     * @param array $params
     * @return array|string
     */
    protected function sendRequest(array $params)
    {
        $response = $this->sendCurl($this->apiUrl . $this->prepareRequest($params));
        if ($this->isJson($response)) {
            return json_decode($response, true);
        }
        return $response;
    }

    /**
     * List all document embeds for the account. Embeds can optionally be filtered on document.
     *
     * @param int $startIndex
     * @param int $pageSize
     * @return array
     */
    public function getEmbedsList(int $startIndex = 0, int $pageSize = 10): array
    {
        return $this->sendRequest([
            'action'             => 'issuu.document_embeds.list',
            'pageSize'           => $pageSize,
            'startIndex'         => $startIndex,
            'responseParamsdata' => 'dataConfigId,width,height,title',
        ]);
    }

    /**
     * @param string $embedId
     * @return string
     */
    public function getEmbedHtml(string $embedId): string
    {
        return $this->sendRequest([
            'action'   => 'issuu.document_embed.get_html_code',
            'embedId'  => $embedId,
        ]);
    }

    /**
     * List all document for the account.
     *
     * @param int $startIndex
     * @param int $pageSize
     * @return array
     */
    public function getDocumentList(int $startIndex = 0, int $pageSize = 10): array
    {
        return $this->sendRequest([
            'action'             => 'issuu.documents.list',
            'pageSize'           => $pageSize,
            'startIndex'         => $startIndex,
            'documentStates'     => 'A',
            'orgDocTypes'        => 'pdf',
            'responseParamsdata' => 'documentId,width,height,title',
        ]);
    }

    /**
     * List all folder for the account.
     *
     * @param int $startIndex
     * @param int $pageSize
     * @return array
     */

    public function getFolderList(int $startIndex = 0, int $pageSize = 10): array
    {
        return $this->sendRequest([
            'action'        =>  'issuu.folders.list',
            'pageSize'      =>  $pageSize,
            'startIndex'    =>  $startIndex,
        ]);
    }

    /**
     * Create document embed
     * 
     * @param string $documentId
     * @param int $startIndex
     * @param int $width
     * @param int $height
     * @return array
     * 
     */

    public function createDocumentEmbed(string $documentId, int $startIndex = 0, int $width = 400, int $height = 270): array
    {
        return $this->sendRequest([
            'action'            => 'issuu.document_embed.add',
            'documentId'        =>  $documentId,
            'readerStartPage'   =>  $startIndex,
            'width'             =>  $width,
            'height'            =>  $height
        ]);
    }

    private function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

$client = new IssuApi();

function division($integer_num)
{
    return intval($integer_num / 100);
}


function get_folder_name($match_key) {

    $client = new IssuApi();
    $folder_list = $client->getFolderList(0, 10);
    $folderId = array();
    $folderName = array();
    $folder_structure = $folder_list['rsp']['_content']['result']['_content'];


    foreach ($folder_structure as $key => $value) {
        //    echo '<pre>', print_r($value['folder']) , '</pre>';
        $value_folder_id = $value['folder']['folderId'];
        $value_folder_name = $value['folder']['name'];

        array_push($folderId, $value_folder_id);
        array_push($folderName, $value_folder_name);
    }

    $combine_array = array_combine($folderId, $folderName);


    foreach($combine_array as $key => $value) {

        if($key == $match_key) {
            return $value;
        }

    } 


}

// var_dump(get_folder_name('874c2196-d21f-4840-bd26-aa9568402be5'));


// echo '<pre> Document', print_r($client->getDocumentList(100, 100)) ,'</pre>';

// echo '<pre> Document List', print_r($client->getEmbedsList(100, 100)) ,'</pre>';


/**
 *Get list of all document Id of document list.
 *
 **/

$document_list_array = array();
$document_total_count = $client->getDocumentList()['rsp']['_content']['result']['totalCount'];

$document_iteration = division($document_total_count);

for ($i = 0; $i <= ($document_iteration * 100); $i = $i + 100) {
    foreach ($client->getDocumentList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) {
        // array_push($document_list_array ,$value['document']['documentId']);

        $document_array = [
            "documentId"    => $value['document']['documentId'],
            "title"         => $value['document']['title'],
            "description"   => isset($value['document']['description']) ? $value['document']['description'] : '',
            'folders'       => isset($value['document']['folders'][0]) ?  $value['document']['folders'][0] : 'uncategorize'
        ];
        // echo '<pre>', print_r($document_array) ,'</pre>';
        array_push($document_list_array, $document_array);
    }
}

// echo 'Document List <pre>', print_r($document_list_array), '</pre>';

/**
 *Get list of all document id of document embed list.
 *
 **/

$document_embed_list_array = array();
$document_embed_total_count = $client->getEmbedsList()['rsp']['_content']['result']['totalCount'];

$document_embed_iteration  = division($document_embed_total_count);


for ($i = 0; $i <= ($document_embed_iteration * 100); $i = $i + 100) {
    foreach ($client->getEmbedsList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) {
        if (!empty($value['documentEmbed']['documentId'])) {
            $document_embed_array = [
                "documentId"    => $value['documentEmbed']['documentId'],
                "id"            => $value['documentEmbed']['id'],
                // "embed_doc"     => $client->getEmbedHtml(85974456)
            ];
            array_push($document_embed_list_array, $document_embed_array);
        }
        
    }
}

// echo 'Document Embed List <pre>', print_r($document_embed_list_array), '</pre>';
// $array_list = array_intersect($document_list_array,$document_embed_list_array);
// echo '<pre>',print_r($array_list),'</pre>';

/**
 * Get list of Merged array of the document list and document embed list.
 * 
 * */
$merge_array = array();

foreach ($document_list_array as $key => &$value_1) {
    foreach ($document_embed_list_array as $key => $value_2) {
        if (!empty($value_2['documentId'])) {
            if ($value_1['documentId'] === $value_2['documentId']) {
                $merge_array[] = array_merge($value_1, $value_2);
            }
        }
    }
}

echo 'Merge array <pre>', print_r($merge_array), '</pre>';
