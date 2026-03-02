<?PHP
/*class media_handler
{
	function convert_media($absolute_path,$filename, $inputpath)
	{
		$outfile = "";
		$outputpath		= 	realpath("../image/videos/flv");
		//$outputpath=$absolute_path."/image/flv";
		$rPath = $absolute_path."/ffmpeg-0.5/ffmpeg.exe";
		$size = "300x400";
		$outfile 	=	$filename;
		$out=explode(".",$outfile);
		
		
		$outfile = $out[0].".flv";
		$ffmpegcmd1 = realpath("../").'\ffmpeg-0.5\ffmpeg.exe -i '.$inputpath."\\".$filename. " -ar 22050 -ab 360 -f flv -s ".$size." ".$outputpath."\\".$outfile;
		
		
		$ret = shell_exec($ffmpegcmd1);
		return $ffmpegcmd1;
	}

}

*/

    function parseVideoEntry($entry) {      
      $obj= new stdClass;
      // get nodes in media: namespace for media information
      $media = $entry->children('http://search.yahoo.com/mrss/');
      $obj->title = $media->group->title;
      $obj->description = $media->group->description;
      
      // get video player URL
      $attrs = $media->group->player->attributes();
      $obj->watchURL = $attrs['url']; 
      
      // get video thumbnail
      $attrs = $media->group->thumbnail[0]->attributes();
      $obj->thumbnailURL = $attrs['url']; 
            
      // get <yt:duration> node for video length
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $sec = $attrs['seconds']; 
	  $min=floor($sec/60);	  
	  $sec=$sec%60;
	  
	  
	  $obj->length = $min.":".$sec; 
      
      // get <yt:stats> node for viewer statistics
      $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->statistics->attributes();
      $obj->viewCount = $attrs['viewCount']; 
      
      // get <gd:rating> node for video ratings
      $gd = $entry->children('http://schemas.google.com/g/2005'); 
      if ($gd->rating) { 
        $attrs = $gd->rating->attributes();
        $obj->rating = $attrs['average']; 
      } else {
        $obj->rating = 0;         
      }
        
      // get <gd:comments> node for video comments
      $gd = $entry->children('http://schemas.google.com/g/2005');
      if ($gd->comments->feedLink) { 
        $attrs = $gd->comments->feedLink->attributes();
        $obj->commentsURL = $attrs['href']; 
        $obj->commentsCount = $attrs['countHint']; 
      }
      
      //Get the author
      $obj->author = $entry->author->name;
      $obj->authorURL = $entry->author->uri;
      
      
      // get feed URL for video responses
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/
      2007#video.responses']"); 
      if (count($nodeset) > 0) {
        $obj->responsesURL = $nodeset[0]['href'];      
      }
         
      // get feed URL for related videos
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/
      2007#video.related']"); 
      if (count($nodeset) > 0) {
        $obj->relatedURL = $nodeset[0]['href'];      
      }
    
      // return object to caller  
      return $obj;      
    }   

?>