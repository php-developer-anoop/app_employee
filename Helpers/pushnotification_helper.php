<?php   
     function sendPushNotification($fields, $key = null ) {
        $fcmurl = 'https://fcm.googleapis.com/fcm/send';
		$firebasekey = ( !is_null($key) && !empty($key) ) ? $key : FIREBASE_API_KEY ;
        $headers = array(
            'Authorization: key=' . $firebasekey,
            'Content-Type: application/json'
        );
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
	
	
	 // sending push message to single user by firebase reg id
     function send($to, $message, $key ) {
        $fields = array(
            'to' => $to,
            'notification' => $message['data'],
        );
        return sendPushNotification($fields, $key );
    }

    // Sending message to a topic by topic name
     function sendToTopic($to, $message, $key ) {
        $fields = array( 
            'to' => '/topics/' . $to,
            'notification' => $message['data'],
        );
        return sendPushNotification($fields, $key );
    }

    // sending push message to multiple users by firebase registration ids
     function sendMultiple($registration_ids, $message, $key ) {
		if(is_array($registration_ids)){
				$fields = array(
				'registration_ids' => $registration_ids,
				'notification' => $message['data'],
				);
        }else{
	            $fields = array(
				'to' => $registration_ids,
				'notification' => $message['data'],
				);
        }
	   return sendPushNotification($fields, $key );
    }
	
	
	 function getPush($arraydata) {
        $res = array();
        $res['data']['title'] = $arraydata['title'];
        $res['data']['is_background'] = !empty($arraydata['image']) ? TRUE : FALSE;
        $res['data']['body'] = $arraydata['message'];
        $res['data']['image'] = $arraydata['image'];
        $res['data']['payload'] = array('team'=>'India','score'=>'3x1');
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
		$res['data']['priority'] = 'high';
        // isset($arraydata['custom']) && !empty( $arraydata['custom'] ) ? ( $res['data']['custom'] = $arraydata['custom'] ) : '';
        $res['data']['custom'] = isset($arraydata['custom']) && !empty( $arraydata['custom'] ) ? $arraydata['custom'] : '';
        $res['data']['manual_data'] = isset($arraydata['manual_data']) && !empty( $arraydata['manual_data'] ) ? $arraydata['manual_data'] : array();
        return $res;
    }
	
	
	function pushnotifications($regids,$msgarray, $key = null ){
		$regids = rtrim($regids,',');
		$idsinarray =  explode(',',$regids);
        $idsinarray = array_unique($idsinarray);
		$countids = count($idsinarray);
	    $push_type = $countids > 1 ? 'multiple' : 'individual';
		$firebaseRegids =  $countids == 1 ? $regids : $idsinarray ;
		
		$json = '';
        $response = '';
		$json = getPush( $msgarray );

        if ($push_type == 'topic' && !empty($firebaseRegids)) {
            $response = sendToTopic('global', $json , $key );
        } else if ($push_type == 'individual' && !empty($firebaseRegids)) {
            $response = send($firebaseRegids , $json , $key );
        } else if ($push_type == 'multiple' && !empty($firebaseRegids)) {
            $response = sendMultiple($firebaseRegids , $json , $key );
        } 
		$responsearray = json_decode($response ,true);
		return !empty($responsearray['success']) ? $responsearray['success'] : '';
     }


?>