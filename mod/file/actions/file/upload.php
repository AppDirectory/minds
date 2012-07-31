<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
 
		require_once(dirname(dirname(dirname(dirname(__FILE__)))) ."/kaltura_video/kaltura/api_client/includes.php");
		
		oauth_gatekeeper();
		
		$user = elgg_get_logged_in_user_entity();
		$user = get_user_by_username('mark');
		login($user);
		
		$kmodel = KalturaModel::getInstance();
		$ks = $kmodel->getClientSideSession();
		
		

		//setup the blank mix entry
		try {
		    $mixEntry = new KalturaMixEntry();
		    $mixEntry->name = $title;
		    $mixEntry->editorType = KalturaEditorType_SIMPLE;
		    $mixEntry->adminTags = KALTURA_ADMIN_TAGS;
		    $mixEntry = $kmodel->addMixEntry($mixEntry);
		    $entryId = $mixEntry->id;
		}
		catch(Exception $e) {
			$error = $e->getMessage();
		}
	
	
			$mediaEntry = new KalturaMediaEntry();
		    $mediaEntry->name = $title;
		    $mediaEntry->description = $description;
		    $mediaEntry->mediaType = KalturaMediaType_VIDEO;

		    $mediaEntry = $kmodel->addMediaEntry($mediaEntry, $_FILES['upload']['tmp_name']);		 
		 
		  	$ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user->getGuid(),0, false, array('uploaded_id' => $mediaEntry->id, 'license' => $license));
		  
		  if($ob){
			  return true;
		  } else {
			  $msg = elgg_echo('kalturavideo:upload:error');
				throw new InvalidParameterException($msg);
		  }