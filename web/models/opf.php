<?php

class OPF {
	public $OPF;

	public function __construct($opf) {
		$tmp = $this->xml2ary($opf);

		// Parse MetaData
		$this->OPF["metadata"]=array(
			"author"=>$tmp["package"]["_c"]["metadata"]["_c"]["dc:creator"]["_v"],
			"title"=>$tmp["package"]["_c"]["metadata"]["_c"]["dc:title"]["_v"],
			"language"=>$tmp["package"]["_c"]["metadata"]["_c"]["dc:language"]["_v"],
			"date"=>$tmp["package"]["_c"]["metadata"]["_c"]["dc:date"]["_v"],
		);

		// Parse Manifest
		foreach ($tmp["package"]["_c"]["manifest"]["_c"]["item"] as $item)
			$this->OPF["manifest"][]=$item["_a"];

		// Parse Spine
		foreach ($tmp["package"]["_c"]["spine"]["_c"]["itemref"] as $spineItem)
			$this->OPF["spine"][]=$spineItem["_a"]["idref"];
	}

	public function getByID($id) {
		foreach ($this->OPF["manifest"] as $item)
			if ($item["id"]==$id)
				return $item;
		return NULL;
	}

	private function xml2ary(&$string) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $string, $vals, $index);
		xml_parser_free($parser);

		$mnary=array();
		$ary=&$mnary;
		foreach ($vals as $r) {
			$t=$r['tag'];
			if ($r['type']=='open') {
				if (isset($ary[$t])) {
					if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
					$cv=&$ary[$t][count($ary[$t])-1];
				} else $cv=&$ary[$t];
				if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
				$cv['_c']=array();
				$cv['_c']['_p']=&$ary;
				$ary=&$cv['_c'];
			} elseif ($r['type']=='complete') {
				if (isset($ary[$t])) { // same as open
					if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
						$cv=&$ary[$t][count($ary[$t])-1];
				} else $cv=&$ary[$t];
				if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
				$cv['_v']=(isset($r['value']) ? $r['value'] : '');

			} elseif ($r['type']=='close') {
				$ary=&$ary['_p'];
			}
		}

		$this->_del_p($mnary);
		return $mnary;
	}

	// _Internal: Remove recursion in result array
	private function _del_p(&$ary) {
		foreach ($ary as $k=>$v) {
			if ($k==='_p') unset($ary[$k]);
			elseif (is_array($ary[$k])) $this->_del_p($ary[$k]);
		}
	}
}