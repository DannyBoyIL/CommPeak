<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class CallService {

    use \App\Traits\Continents;

    private $urls = [
        'ipstack' => 'http://api.ipstack.com/',
        'geoip' => 'https://api.ipgeolocation.io/ipgeo'
    ];
    private $accessKeys = [
        'ipstack' => 'ed09e98ccc0c3f163c4d575a764f3629',
        'geoip' => '3aefd4642bc04d51a76996aee82b13f7'
    ];

    public function __construct() {
        
    }

    public function readCsv($fileName) {

        $name = '../public/uploads/' . $fileName;
        $records = [];

        if (($handle = fopen($name, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $records[] = $data;
            }
            fclose($handle);
        }
        return $records;
    }

    public function filter(array $calls): array {

        $callback = [];
        $records = [];
        
        foreach ($calls as $i => $call) {

            $id = $call->getCustumerId();
            $continentIp = $this->getContinentByIp($call->getIp());
            $coninentPhone = $this->getContinentByPhone($call->getPhone());

            if (!key_exists($id, $records)) {
                $records[$id] = [];
                $records[$id]['calls_number'] = 0;
                $records[$id]['duration_number'] = 0;
            }

            $records[$id]['total_calls'] = isset($records[$id]['total_calls']) ? $records[$id]['total_calls'] += 1 : 1;
            $records[$id]['total_duration'] = isset($records[$id]['total_duration']) ? $records[$id]['total_duration'] += $call->getDuration() : $call->getDuration();

            if ($coninentPhone == $continentIp) {
                $records[$id]['calls_number'] += 1;
                $records[$id]['duration_number'] = $call->getDuration();
            }
        }      

        foreach ($records as $i => $record) {
            $array = [];
            $array[] = '';
            $array[] = $i;
            $array[] = $record['calls_number'];
            $array[] = $record['duration_number'];
            $array[] = $record['total_calls'];
            $array[] = $record['total_duration'];
            $callback[] = $array;
        }
        return $callback;
    }

    public function getContinentByIp(string $ip): string {

        try {
            $client = HttpClient::create();

            $response = $client->request('GET', $this->urls['geoip'], [
                'query' => [
                    'apiKey' => $this->accessKeys['geoip'],
                    'ip' => $ip
                ],
            ]);

            $content = $response->getContent();
            $callback = json_decode($content);
            return $callback->continent_code;
            
        } catch (TransportExceptionInterface $e) {
            dd($e);
        }
    }

}
