<?php

function is_mfdcf_previous_pro_version_not_expired(){

    $license = get_option("dcfme_license_key");
    $license = !empty($license) ? unserialize($license) : array();
    $dcme_license_key = isset($license['license_key'])?$license['license_key']: "0";
    $license_expire_unix = isset($license['expire'])?$license['expire']: "0";
    $license_expire = "expired";
    
    if(!empty($license_expire_unix)){
        $license_expire = date('d-m-Y', $license_expire_unix );
        
        $now = new DateTime();
        $unix_now = strtotime(date("d-m-Y"));
        $expire_date = new DateTime($license_expire);
        $interval = date_diff($now, $expire_date);
            
        if( $unix_now > $license_expire_unix ){
            $license_expire = "expired";
        }
    }

    if (!empty($dcme_license_key) && $license_expire !== 'expired') {
    	return true;
    } else {
    	return false;
    }

}