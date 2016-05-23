<?php
/**
 * Created by PhpStorm.
 * User: thinlt
 * Date: 4/28/2016
 * Time: 10:35 AM
 */
namespace Model\Github;

class Repos extends \Model\ObjectAbstract {

    static $root_path = 'repo'; //root path to save repositorie zipball files

    public function releases(\Model\Repo $repo){

        $url = 'https://api.github.com/repos/'.$repo->getData('owner').'/'.$repo->getData('repo').'/releases';

        $response = \Httpful\Request::get($url)
            ->expectsJson()
            ->Authorization("token ".\Model\Github\Token::$token)                // Add in a custom header X-Example-Header
            //->withXAnotherHeader("Another Value")       // Sugar: You can also prefix the method with "with"
            //->addHeader('X-Or-This', 'Header Value')    // Or use the addHeader method
            /*->addHeaders(array(
                'X-Header-1' => 'Value 1',              // Or add multiple headers at once
                'X-Header-2' => 'Value 2',              // in the form of an assoc array
            ))*/
            ->send();

        return $response->body;
    }

    /**
     * download and save to disk
     */
    public function download($zipball_url, $owner = '', $repo = ''){
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $repoPath = str_replace('https://api.github.com/repos/', '', $zipball_url); //remove https://github.com/
        $repoPath = str_replace('http://api.github.com/repos/', '', $repoPath); //remove http://github.com/
        $repoPaths = explode('/', $repoPath);
        if(count($repoPaths) >= 4){
            if(!$owner){
                $owner = $repoPaths[0];
            }
            if(!$repo){
                $repo = $repoPaths[1];
            }

            if(!$owner || !$repo){
                return false;
            }

            $format = ($repoPaths[2]) ? $repoPaths[2] : 'zipball';
            $version = ($repoPaths[3]) ? $repoPaths[3] : '1.0.0';

            if(count(explode('.', $version)) == 3){
                $version .= '.0';
            }

            $rootSaveDir = BP.DS.static::$root_path.DS.$owner.DS.$repo;
            $saveFile = $rootSaveDir.DS.$version.DS.$format.'.zip';

            if(!$this->checkExist($saveFile)){
                $data = $this->_download($zipball_url, \Model\Github\Token::$token);
                $this->writeFile($saveFile, $data);
            }

            return true;
        }

        return false;
    }

    protected function writeFile($path_to_save, $file_content = ''){
        if($path_to_save){
            $dir = dirname($path_to_save);
            if(!is_dir($dir)){
                mkdir($dir , 0755, true);
            }
            $file = fopen($path_to_save, "w") or die("Unable to open file!");
            fwrite($file, $file_content);
            fclose($file);
            return true;
        }
        return false;
    }

    protected function checkExist($file){
        return is_file($file);
    }

    private function exec_redirects($ch, &$redirects) {
        $data = curl_exec($ch);

        if($data === false){
            $curlErrorString = curl_error($ch);
            throw new \Exception('Connection error: '.$curlErrorString);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            list($header) = explode("\r\n\r\n", $data, 2);
            $matches = array();
            preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
            $url = trim(str_replace($matches[1], "", $matches[0]));
            $url_parsed = parse_url($url);
            if (isset($url_parsed)) {
                curl_setopt($ch, CURLOPT_URL, $url);
                $redirects++;
                return $this->exec_redirects($ch, $redirects);
            }
        }

        list(, $body) = explode("\r\n\r\n", $data, 2);
        return $body;
    }

    private function _download($url, $token){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: token '.$token, 'User-Agent: GhRepoDownloader'));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = $this->exec_redirects($ch, $out);
        curl_close($ch);
        return $data;
    }

}