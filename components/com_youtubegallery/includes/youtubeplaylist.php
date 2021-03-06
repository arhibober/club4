<?php
/**
 * YoutubeGallery
 * @version 3.1.1
 * @author DesignCompass corp< <admin@designcompasscorp.com>
 * @link http://www.designcompasscorp.com
 * @license GNU/GPL
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);

require_once(JPATH_SITE.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php');

class VideoSource_YoutubePlaylist
{
	public static function extractYouTubePlayListID($youtubeURL)
	{
				
		$arr=YouTubeGalleryMisc::parse_query($youtubeURL);
		
		$p=$arr['list'];
		
		if(strlen($p)<3)
			return '';
		
		if(substr($p,0,2)!='PL')
			return ''; //incorrect playlist ID
		 
	    return substr($p,2); //return without leading "PL"
	}
	
	public static function getVideoIDList($youtubeURL,$optionalparameters,&$playlistid)
	{
		$optionalparameters_arr=explode(',',$optionalparameters);
		
		$videolist=array();
		
		$spq=implode('&',$optionalparameters_arr);
		
		$videolist=array();
		
		$playlistid=VideoSource_YoutubePlaylist::extractYouTubePlayListID($youtubeURL);
		if($playlistid=='')
			return $videolist; //playlist id not found
		
		$url = 'http://gdata.youtube.com/feeds/api/playlists/'.$playlistid.($spq!='' ? '?'.$spq : '' ) ; //&max-results=10;
		
		$xml=false;
		$htmlcode=YouTubeGalleryMisc::getURLData($url);

		if(strpos($htmlcode,'<?xml version')===false)
		{
			if(strpos($htmlcode,'Invalid id')===false)
				return 'Cannot load data, Invalid id';

			return 'Cannot load data, no connection';
		}
		
		$xml = simplexml_load_string($htmlcode);
		
		if($xml){
			foreach ($xml->entry as $entry)
			{
				/*
				if(isset($entry->link[0]))
				{
					$link=$entry->link[0];
					$attr = $link->attributes();
					
					$videolist[] = $attr['href'];
				}
				*/
				
				//
				$media = $entry->children('http://search.yahoo.com/mrss/');
				$link = $media->group->player->attributes();
				if(isset($link['url']))
				{
					$videolist[] = $link['url'];
				}
				//
				
			}
		}
		
		return $videolist;
		
	}
	
	


}


?>