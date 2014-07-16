<?php
class articlemodel extends model{

	public function __construct(){
	$this->data['articlesList']=array();
	}
	
	
	public function getarticle($selector=null){
		$list = $this->getList();
		$this->data['article'] = array();
	
		if ($selector==null)$selector=0;
		
		foreach ($list as $k=>$v) {
			
			if (($selector==strval($k))||($v['id']==$selector)||(strpos(strtolower($v['name']),strtolower($selector)) !== false)){
				$this->data['article']=$v;
				break;
				}
			} 
		if ((!isset($this->data['article']['id']))&&(isset($list))){

			$this->data['article']['id']=$list[0]['id'];
			$this->data['article']['extension']=$list[0]['extension'];
			$article=file_get_contents($list[0]['id']);
		//	$article = utf8_encode($article);
		}else{ 
			if ($this->data['article']['source']=='file'){
				$article=file_get_contents($this->data['article']['id']);
			//	$article = utf8_encode($article);
			} 
			if ((isset($this->data['article']['source']))&&($this->data['article']['source']=='db')){
				$article = $this->dbQuery("SELECT page.text FROM page WHERE page.id=" . $this->data['article']['id']);
				$article =	$article[0]['text'];
				$article = imap_utf8($article); 
			} 
			}
			if (($this->data['article']['extension']=="txt") || ($this->data['article']['extension']=="text/plain")){
				$this->data['article'] = $this->format($article);
			return $this->data['article'];
			} else { 
				$this->data['article'] = $article;
			return $this->data['article'];
			}
		
	}
	
	
// get file list in format array(name=>filename, extension=>fileext, source=>file, id=>filename)
		protected function getFilesList(){
		$this->data['FilesList'] = Array ();
			$files = scandir(PAGE_PATH);
			foreach ($files as $file) {
				if (($file!= "")&&($file!=".")&&($file!="..")){
				$path = PAGE_PATH . $file;
			
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				if((file_exists($path))&&(($ext == "html")||($ext == "txt"))){
					$aName= str_replace(array (".html",".txt"),'',$file);
					array_push($this->data['FilesList'],array('name'=>$aName,'extension'=>$ext,'source'=>'file','id'=>$path));	
				}
			}
		}
		return $this->data['FilesList'];
	}
	
// get file list in format array(name=>link, extension=>mime, sourse=>db, id=>id)
	public function getRecordsList(){
	$queryresult = Array ();
	$this->data['RecoredsList'] = Array ();


	$queryresult = $this->dbQuery(' SELECT link.link,page.id,page.mime FROM link,page WHERE link.page_id=page.id');
	
	foreach ($queryresult as $record){
	
	array_push($this->data['RecoredsList'],array('name'=>ucfirst($record['link']),'extension'=>$record['mime'],'source'=>'db','id'=>$record['id']));
	}
		
	return $this->data['RecoredsList'];
	
	}
		
	public function getList(){
	
		$files = $this->getFilesList();
		$records = $this->getRecordsList();
		$this->data['articlesList'] = $files;
		$this->data['articlesList'] = array_merge ($this->data['articlesList'],$records);

		usort($this->data['articlesList'], function($a, $b) {
		   	return (strcasecmp($a['name'],$b['name']));
		});
		
	
		return $this->data['articlesList'];
	}
	
	public function format($article) {

// links	
		$article= preg_replace( 
		"/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", 
		"<a href=\"$0\" >$0</a>", 
		$article);
// emails    
	$article= preg_replace('/(\S+@\S+\.\S+)/', 
		'<a href="mailto:$1">$1</a>', 
		$article);
// paragraphs
		$article= str_replace("\r\n","\n",$article);
		$article= str_replace("\r","\n",$article);
		$article= preg_replace('/\n{2,}/',"\n\n",$article);
		$article= preg_replace('/\n(\s*\n)+/', '</p><p>', $article);
		$article = '<p>'.$article.'</p>';
		$rgx=true;
// lists
		$article= preg_replace('!<p>\s*\n*([\\*])!', '<ul>$1', $article);
		$article= preg_replace('!(<ul>.*\s*\n*[\\*].*?)</p>!', '$1</ul>', $article);
		$article= preg_replace('~((\n+)|(<ul>))\\*~', '$1<li>', $article);
//		$article= preg_replace('!(<ul>.*\s*\n*[\\*].*?)</p>!', '$1</ul>', $article);
		
// headers   	
		$article = preg_replace_callback(
			'!<p>(#+)(.*)</p>!',
				function ($m) {
					return '<h'.@strlen($m[1]).'>'.$m[2]. '</h'.@strlen($m[1]).'>';
				},
			$article
			);
// first level headers		
		$article = preg_replace("~\n*(==+|--+).*</p>~", "</h1>", $article);
    	$article = preg_replace("!<p>(.*</h1>)!", "<h1>$1", $article);

// breaks
		$article= preg_replace('/\n(-+.*?)/','<br>$1', $article);
		
		$article=(html_entity_decode($article));
		
		
		return $article;
	}
}
