<?php

/**
 * 上传临时文件, 账号需要认证，文件会在3天后删除
 * 
 * @param unknown $accessToken
 * @param unknown $type 图片（image）、语音（voice）、视频（video）和缩略图（thumb）
 * @param unknown $media 文件绝对路径
 * @return string MEDIA_ID
 */
function ydwx_media_upload($accessToken, $type, $media){
    if( ! YDWX_WEIXIN_IS_AUTHED){
        throw new YDWXException("上传文件需要认证账号");
    }
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."media/upload?access_token={$accessToken}&type=$type", 
            array("media"=>"@".$media) ,true);
    $msg  = new YDWXResponse($info); 
    if($msg->isSuccess()){
        return $msg->media_id; 
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 下载临时文件
 * 
 * @param unknown $accessToken
 * @param unknown $mediaid
 * @param string $isVideo
 * @return boolean|content
 */
function ydwx_media_get($accessToken, $mediaid, $isVideo=false){
    $http    = new YDHttp();
    $content = $http->get( ($isVideo ? YDWX_YDWX_WEIXIN_BASE_URL2 : YDWX_WEIXIN_BASE_URL)."media/get?access_token={$accessToken}&media_id={$mediaid}");
    $info    = json_decode($content, true);
    if( array_key_exists("errcode", $info))return false;
    return $content;
}