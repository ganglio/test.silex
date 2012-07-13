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
		$this->OPF = new OPF($this->book->getFromIndex(array_search("content.opf", $this->tree)));
	}

	public function getTree() {
		return $this->tree;
	}

	public function getOPF() {
		return $this->OPF;
	}
}

class EPUBException extends Exception {}