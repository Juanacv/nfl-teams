<?php
class NFL_TEAMS_LAYOUT {
    protected $url;
    protected $version;
    protected $plugin_name;

    function __construct($url, $version, $plugin_name)
    {
        $this->url = $url;    
        $this->version = $version;
        $this->plugin_name = $plugin_name;
    }
    /**
     * Curl call to a URL API
     * @param string $method GET/POST/PUT methods
     * @param string $url URL API
     * @param array $data params send to API
     */
    protected function callAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }
    /**
     * Make the API call and retrieve the data
     */
    protected function getData() {
        $get_data = $this->callAPI('GET', $this->url, false);
        $response = json_decode($get_data, true);
        $teams = $response['results']['data']['team'];
        $afc = ['East'=>[],'West'=>[],'North'=>[],'South'=>[]];
        $nfc = ['East'=>[],'West'=>[],'North'=>[],'South'=>[]];
        foreach ($teams as $team) {
            $name = $team['nickname'];
            if ($team['nickname']=='Football Team') {
                $name = $team['display_name'];
            }
            if ($team['conference']=='National Football Conference') {
                array_push($nfc[$team['division']],$name);
                
            }
            else {
                array_push($afc[$team['division']],$name);
            }
        }
        return ['afc'=>$afc,'nfc'=>$nfc];
    }
    /**
     * Enqueue Bootstrap js library
     */
    public function enqueueScripts() {
        wp_enqueue_script( $this->plugin_name, plugins_url('js/bootstrap.bundle.min.js',__FILE__), array( 'jquery' ), $this->version, false );
    }

    /**
     * Enqueue boostrap css and custom css
     */
    public function enqueueStyles() {
        wp_enqueue_style( $this->plugin_name.'-bootstrap', plugins_url('css/bootstrap.min.css',__FILE__), array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name.'-nfl', plugins_url('css/nflteams.css',__FILE__), array($this->plugin_name.'-bootstrap'), $this->version, 'all' );
    }
    /**
     * Returns html layout with NFL teams
     */
    public function buildLayout() {
        $nfl = $this->getData();
        $afc = $nfl['afc'];
        $nfc = $nfl['nfc'];
        $html = '<article id="acme-main">';
        $html .= '<header>';
        $html .= '<nav>';
        $html .= '<div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">';
        $html .= '<button class="nav-link active" id="nav-afc-tab" data-bs-toggle="tab" data-bs-target="#nav-afc" type="button" role="tab" aria-controls="nav-afc" aria-selected="true">ACF</button>';
        $html .= '<button class="nav-link" id="nav-nfc-tab" data-bs-toggle="tab" data-bs-target="#nav-nfc" type="button" role="tab" aria-controls="nav-nfc" aria-selected="false">NFC</button>';
        $html .= '</div>';
        $html .= '</nav>';
        $html .= '</header>';
        $html .= '<div class="tab-content" id="nav-tabContent">';
        $html .= '<div class="tab-pane fade show active" id="nav-afc" role="tabpanel" aria-labelledby="nav-afc-tab">';
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">AFC East</div>';
        for ($i=0; $i < count($afc['East']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$afc['East'][$i].'</div>';
        }
        $html .= '</div>';
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">AFC West</div>';
        for ($i=0; $i < count($afc['West']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$afc['West'][$i].'</div>';
        }
        $html .= '</div>';   
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">AFC North</div>';
        for ($i=0; $i < count($afc['North']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$afc['North'][$i].'</div>';
        }
        $html .= '</div>';      
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">AFC South</div>';
        for ($i=0; $i < count($afc['South']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$afc['South'][$i].'</div>';
        }
        $html .= '</div>';      
        $html .= '</div>';    
        $html .= '<div class="tab-pane fade" id="nav-nfc" role="tabpanel" aria-labelledby="nav-nfc-tab">';          
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">NFC East</div>';
        for ($i=0; $i < count($nfc['East']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$nfc['East'][$i].'</div>';
        }
        $html .= '</div>';
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">NFC West</div>';
        for ($i=0; $i < count($nfc['West']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$nfc['West'][$i].'</div>';
        }
        $html .= '</div>';   
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">NFC North</div>';
        for ($i=0; $i < count($nfc['North']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$nfc['North'][$i].'</div>';
        }
        $html .= '</div>';      
        $html .= '<div class="row row-no-margins mb-3">';
        $html .= '<div class="col-12 themed-grid-col border-grid">NFC South</div>';
        for ($i=0; $i < count($nfc['South']); $i++) {
            $html .= '<div class="col-12 themed-grid-col border-grid background-grid">'.$nfc['South'][$i].'</div>';
        }
        $html .= '</div>';      
        $html .= '</div>';       
        $html .= '</div>';   
        $html .= '</article>';   
        $html .= '<footer id="footer-acme" class="mx-auto"><span>ACME CO. '.date('Y').'</span></footer>';   
        return $html;
    }
}
?>
