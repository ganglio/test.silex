<?php

require_once("opf.php");

class EPUB {
	private $book;
	private $tree;
	private $OPF;

	public function __construct($filename) {
		$this->book=new ZipArchive();
		if ($this->book->open($filename) != TRUE )
			throw new EPUBException("Unable to open $filename");//*/

		// Build Files Tree
		$this->tree = array();
		for ($i=0; $i<$this->book->numFiles; $i++)
			$this->tree[$i]=$this->book->getNameIndex($i);

		// Create OPF
		$this->OPF = new OPF($this->getContentByName("content.opf"));
	}

	public function getCover() {
		$cover = $this->OPF->getByID("cover");
		$cover_mime=explode("/",$cover["media-type"]);

		if ($cover_mime[0]!='image')
		$cover = $this->OPF->getByID("cover-image");

		$cover_data = $this->getContentByName($cover["href"]);
		$cover_image = imagecreatefromstring($cover_data);
		$cover_size = array("W"=>imagesx($cover_image),"H"=>imagesy($cover_image));

		$max_width = 150;
		$max_height = 225;

		$ratioh = $max_height/$cover_size["H"];
		$ratiow = $max_width/$cover_size["W"];
		$ratio = min($ratioh, $ratiow);

		$cover_size["NW"] = intval($ratio*$cover_size["W"]);
		$cover_size["NH"] = intval($ratio*$cover_size["H"]);

		$cover_resized = imagecreatetruecolor($cover_size["NW"], $cover_size["NH"]);
		imagecopyresampled(
			$cover_resized,
			$cover_image,
			0,0,0,0,
			$cover_size["NW"],$cover_size["NH"],$cover_size["W"],$cover_size["H"]);

		ob_start();
		imagejpeg($cover_resized);
		$out = ob_get_clean();
		imagedestroy($cover_image);
		imagedestroy($cover_resized);
		return $out;
	}

	public function getContentByName($name) {
		return $this->book->getFromIndex(array_search($name, $this->tree));
	}

	public function getTree() {
		return $this->tree;
	}

	public function getOPF() {
		return $this->OPF;
	}
}

class EPUBException extends Exception {}