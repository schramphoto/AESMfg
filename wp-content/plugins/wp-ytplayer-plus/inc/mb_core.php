<?php
/**
 * Created by mb.ideas.
 * User: pupunzi
 * Date: 10/12/16
 * Time: 22:28
 */

if (!class_exists("ytp_mb_core")) {

    class ytp_mb_core
    {
      function __construct($name_space, $lic_key, $plugin_base)
      {
        $this->name_space = $name_space;
        $this->lic_key = $lic_key;
        $this->base = $plugin_base;

        $this->server_url = 'https://pupunzi.com/wpPlus/controller.php';

        $dir_name = dirname($plugin_base);
        $lic_file_path = $dir_name . "/".$name_space.".lic";
        $this->lic_path = $lic_file_path;

        if(!extension_loaded('openssl'))
        {
          die ('This plug-in needs the Open SSL PHP extension to work. Activate this module or remove this plug-in');
        }
      }

      public function get_lic_domain(){
        // Set unique string for this site
        $lic_domain = $_SERVER['HTTP_HOST'];
        if(!isset($lic_domain) || empty($lic_domain))
          $lic_domain = $_SERVER['SERVER_NAME'];
        if(!isset($lic_domain) || empty($lic_domain))
          $lic_domain = get_bloginfo('name');

        return $lic_domain;
      }

      public function validate_local_lic(){
        global $lic_domain;

        $lic_key = $this->lic_key;
        $xxx = 0;

        if (isset($lic_key) && !empty($lic_key)) {
          $lic = $this->readLic();

          if(!$lic || !$lic["lic_state"]) //The lic has probably the wrong encryption reload it from the server
            $lic = $this->get_lic_from_server();

          if($lic)
            $xxx = ((($lic["lic_domain"] == $lic_domain) || strpos($lic_domain, 'localhost')!== false ) || ($lic["lic_type"] == "DEV" && $lic["lic_theme"] == get_template())) && $lic["plugin_prefix"] == $this->name_space  && $lic["lic_state"] == "ACTIVE";
          else //The server can not be contacted
            $xxx = true;
        }

        return $xxx;
      }

      /**
       * @return bool|mixed|string
       */
      public function get_lic_from_server()
      {

        if(!$this->lic_key)
          return false;

        $data = array('lic_key' => $this->lic_key, 'CMD' => 'UPDATE-LIC-ENCR');

        $lic = false;

        if(function_exists("curl_version")) {

          $ch = curl_init( $this->server_url );
          curl_setopt( $ch, CURLOPT_POST, 1);
          curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data) );
          curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt( $ch, CURLOPT_HEADER, 0);
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

          $response = curl_exec( $ch );

        } else {

          $options = array(
            'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data),
            ),
          );
          $context  = stream_context_create($options);
          $response = file_get_contents($this->server_url, false, $context);
        }

        //and save it to the correct location
        if($response) {
          $this->storeLic($response);
          $lic = $this->decrypt($response, $this->lic_key);
          $lic = json_decode($lic,true);
        }
        return $lic;
      }

      /**
       * @return bool|mixed|string
       */
      function decrypt_on_server()
      {

        if(!$this->lic_key)
          return false;

        $data = array('lic_key' => $this->lic_key, 'CMD' => 'DECRIPT-LIC');

        if(function_exists("curl_version")) {

          $ch = curl_init( $this->server_url );
          curl_setopt( $ch, CURLOPT_POST, 1);
          curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data) );
          curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt( $ch, CURLOPT_HEADER, 0);
          curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

          $response = curl_exec( $ch );

        } else {

          $options = array(
            'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data),
            ),
          );
          $context  = stream_context_create($options);
          $response = file_get_contents($this->server_url, false, $context);
        }

        return $response;

      }

      /**
       * @return bool|mixed|string
       */
      public function readLic()
      {
        $lic_file_path = $this->lic_path;
        $decr_lic = false;

        if (file_exists($lic_file_path)) {
          $lic = file_get_contents($lic_file_path);
          $decr_lic = $this->decrypt($lic, $this->lic_key);

          if($decr_lic) {
            $decr_lic = str_replace(array(' ', "\n", "\t", "\r"), '', $decr_lic);
            $decr_lic = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $decr_lic);
            $decr_lic = json_decode(strval($decr_lic), true);
          }
        }
        return $decr_lic;
      }

      /**
       * @param null $kryptLic
       */
      public function storeLic($kryptLic = null)
      {
        if(!$kryptLic)
          $kryptLic = $_POST["kryptLic"];
        // save lic into file
        $content = $kryptLic;
        $fp = fopen($this->lic_path, "wb");
        fwrite($fp, $content);
        fclose($fp);
      }

      /**
       * @param $data
       * @param $password
       * @return string
       */
      public function decrypt($data, $password)
      {
        $data = base64_decode($data);
        $salt = substr($data, 8, 8);
        $ct = substr($data, 16);
        $key = md5($password . $salt, true);
        $iv = md5($key . $password . $salt, true);
        try {
          $pt = openssl_decrypt($ct, 'aes128', $key, true, $iv);
        } catch (Exception $e){
          $pt = $this->decrypt_on_server();
        }
        return $pt;
      }
    }
}
