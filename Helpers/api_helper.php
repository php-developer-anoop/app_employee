<?php 

  if(!function_exists('checkPayload')){
        function checkPayload(){
            $response = [];
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                $response["status"] = false;
                $response["message"] = "Bad Request";
                echo json_encode($response);
                exit();
            }
            //handle request data
            $requestData = file_get_contents("php://input");
            $post = json_decode($requestData, true);
            if (empty($post)) {
                $response["status"] = false;
                $response["message"] = "No Payload";
                echo json_encode($response);
                exit();
            }
            $checkHeaders = checkHeaders();
            if(empty($checkHeaders)){
                return $post;
            }
        }
    }

    if(!function_exists('checkHeaders')){
        function checkHeaders(){
            $response = [];
            $headersList = apache_request_headers(); 
            
            if(empty($headersList['X-Pid'])){
                $response['status'] = false;
                $response['message'] = 'Invalid Auth Token!';
                echo json_encode($response);
                exit;
            }
           // print_r($headersList);die;
            $xPid = explode("Bearer",$headersList['X-Pid']);
            $allowedXPID = trim($xPid[1]);
            $MatchedHeaderList = [];
            $allowedHeaders = ['CONTENT-TYPE','X-PID']; 
            $matchHeadersCount = 0; 
            foreach ($headersList as $key => $value) {
                if( in_array( strtoupper($key), $allowedHeaders ) ){
                      $MatchedHeaderList[strtoupper($key)] = $value;
                      $matchHeadersCount += 1;
                }
            }

            if(  $matchHeadersCount == 0 || $matchHeadersCount < 2){
                $response['status'] = false;
                $response['message'] = 'Headers Not Available!';
                echo json_encode($response);
                exit;
            }
            if( in_array( 'CONTENT-TYPE', $allowedHeaders ) && ($MatchedHeaderList['CONTENT-TYPE'] != 'application/json' ) ){
                $response['status'] = false;
                $response['message'] = 'Invalid Auth Token!';
                echo json_encode($response);
                exit;
            }
            else if( in_array( 'X-PID', $allowedHeaders ) && ($allowedXPID != '3ad29d7c877485e5c7ac18bcfea91a80' ) ){
                $response['status'] = false;
                $response['message'] = 'Invalid Auth Token!';
                echo json_encode($response);
                exit;
            }
        }
    }


    if(!function_exists('checkFormPayload')){
        function checkFormPayload($post){
            $response = [];
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                $response["status"] = false;
                $response["message"] = "Bad Request";
                echo json_encode($response);
                exit();
            }
            if (empty($post)) {
                $response["status"] = false;
                $response["message"] = "No Payload";
                echo json_encode($response);
                exit();
            }
            $checkHeaders = checkFormHeaders();
            if(empty($checkHeaders)){
                return $post;
            }
        }
    }

    if(!function_exists('checkFormHeaders')){
        function checkFormHeaders(){
            $response = [];
            $headersList = apache_request_headers(); 
            if(empty($headersList['X-Pid'])){
                $response['status'] = false;
                $response['message'] = 'Invalid Auth Token!';
                echo json_encode($response);
                exit;
            }
            $xPid = explode("Bearer",$headersList['X-Pid']);
            $allowedXPID = trim($xPid[1]);
            $MatchedHeaderList = [];
            $allowedHeaders = ['X-PID']; 
            $matchHeadersCount = 0; 
            foreach ($headersList as $key => $value) {
                if( in_array( strtoupper($key), $allowedHeaders ) ){
                      $MatchedHeaderList[strtoupper($key)] = $value;
                      $matchHeadersCount += 1;
                }
            }

            if(  $matchHeadersCount == 0 || $matchHeadersCount < 1){
                $response['status'] = false;
                $response['message'] = 'Headers Not Available!';
                echo json_encode($response);
                exit;
            }
            // if( in_array( 'CONTENT-TYPE', $allowedHeaders ) && ($MatchedHeaderList['CONTENT-TYPE'] != 'application/json' ) ){
            //     $response['status'] = false;
            //     $response['message'] = 'Invalid Auth Token!';
            //     echo json_encode($response);
            //     exit;
            // }
            // else
            if( in_array( 'X-PID', $allowedHeaders ) && ($allowedXPID != '3ad29d7c877485e5c7ac18bcfea91a80' ) ){
                $response['status'] = false;
                $response['message'] = 'Invalid Auth Token!';
                echo json_encode($response);
                exit;
            }
        }
    }





    if(!function_exists('getOtpInterval')){
        function getOtpInterval($otp_time ){
            $current_time = date('Y-m-d H:i:s');
            $from = strtotime( $otp_time );
            $to = strtotime( $current_time );
            return  $diffrence = round(abs($to - $from) / 60);
        }
    }
    
    if(!function_exists('getDateDiff')){
        function getDateDiff( $date , $days ){
            return date('Y-m-d', strtotime($date . ' +'.$days.' day'));
        }
    }

    ?>