<?php

/**
 (c) DHTMLX Ltd, 2011
 Licensing: You allowed to use this component for free under GPL or you need to obtain Commercial/Enterprise license to use it in non-GPL project
 Contact: sales@dhtmlx.com
 **/




class SpreadsheetCfg {

	private $settings = Array(
		'version' => 0.1,
		'pattern' => '/\[\[spreadsheet\s*\??\s*(.*)\]\]/Ui',
		'pattern_param' => '/\&([^&=]+)=([^\&=]+)/',
		'first_time' => true,
		'width' => '100%',
		'height' => 'auto',
		'skin' => 'dhx_skyblue',
		'queries' => Array(
			"CREATE TABLE IF NOT EXISTS `#__dhx_data` (
			  `sheetid` varchar(255) DEFAULT NULL,
			  `columnid` int(11) DEFAULT NULL,
			  `rowid` int(11) DEFAULT NULL,
			  `data` varchar(255) DEFAULT NULL,
			  `style` varchar(255) DEFAULT NULL,
			  `parsed` varchar(255) DEFAULT NULL,
			  `calc` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`sheetid`,`columnid`,`rowid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8",

			"CREATE TABLE IF NOT EXISTS `#__dhx_header` (
			  `sheetid` varchar(255) DEFAULT NULL,
			  `columnid` int(11) DEFAULT NULL,
			  `label` varchar(255) DEFAULT NULL,
			  `width` int(11) DEFAULT NULL,
			  PRIMARY KEY (`sheetid`,`columnid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8",

			"CREATE TABLE IF NOT EXISTS `#__dhx_sheet` (
			  `sheetid` varchar(255) NOT NULL,
			  `userid` int(11) DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  `key` varchar(255) DEFAULT NULL,
			  `cfg` varchar(512) DEFAULT NULL,
			  PRIMARY KEY (`sheetid`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",

			"INSERT INTO `#__dhx_sheet` VALUES ('demo_sheet', null, null, 'any_key', null)",

			"CREATE TABLE IF NOT EXISTS `#__dhx_user` (
			  `userid` int(11) NOT NULL AUTO_INCREMENT,
			  `apikey` varchar(255) DEFAULT NULL,
			  `email` varchar(255) DEFAULT NULL,
			  `secret` varchar(64) DEFAULT NULL,
			  `pass` varchar(64) DEFAULT NULL,
			  PRIMARY KEY (`userid`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",
			
			"CREATE TABLE IF NOT EXISTS `#__dhx_triggers` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `sheetid` varchar(255) DEFAULT NULL,
			  `trigger` varchar(10) DEFAULT NULL,
			  `source` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8"
		)
	);

	public function get($name) {
		if (isset($this->settings[$name]))
			return $this->settings[$name];
		return false;
	}

	public function set($name, $value) {
		$this->settings[$name] = $value;
	}

	public function get_spreadsheet_client() {
		$uid = $this->uid();
		$cfg = "var cfg = {\n\t";
		$cfg .= "sheet: '".($this->get('id') ? $this->get('id') : '1')."',\n\t";
		$cfg .= "dhx_rel_path: '{$this->get('plugin')}codebase/',\n\t";
		$cfg .= "parent: 'spreadsheet_box_{$uid}',\n\t";
		$cfg .= "load: '{$this->get('connector')}',\n\t";
		$cfg .= "save: '{$this->get('connector')}',\n";
		$cfg .= "skin: '".($this->get('skin') ? $this->get('skin') : 'dhx_skyblue')."',\n";
		$cfg .= "math: ".($this->get('math') ? $this->get('math') : 'false').",\n";
		$cfg .= "autowidth: ".($this->get('autowidth') ? $this->get('autowidth') : 'false').",\n";
		$cfg .= "autoheight: ".($this->get('autoheight') ? $this->get('autoheight') : 'false')."\n";
		$cfg .= "}\n\n";

		$include = "";
		if ($this->get('first_time')) {
			$include .= "<script src='{$this->get('plugin')}codebase/spreadsheet.php?load=js' type='text/javascript' charset='utf-8'></script>\n";
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/dhtmlx_core.css' type='text/css' charset='utf-8'>\n";
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/dhtmlxspreadsheet.css' type='text/css' charset='utf-8'>\n";
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/dhtmlxgrid_reset.css' type='text/css' charset='utf-8'>\n\n";
		}
		if ($this->get('skin') == 'dhx_web') {
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/dhtmlxspreadsheet_dhx_web.css' type='text/css' charset='utf-8'>\n";
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/skins/dhtmlxgrid_dhx_web.css' type='text/css' charset='utf-8'>\n";
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}codebase/skins/dhtmlxtoolbar_dhx_web.css' type='text/css' charset='utf-8'>\n";
		}

		if ($this->get('color_scheme') !== false) {
			$include .= "<link rel='stylesheet' href='{$this->get('plugin')}skins/{$this->get('skin')}/{$this->get('color_scheme')}/dhtmlx_custom.css' type='text/css' charset='utf-8'>\n";
		}
		
		$init = "var dhx_sh;\n";
		$init .= "function onload_func_{$uid}() {\n\t";
		$init .= "window.setTimeout(function() {\n\t\t";
		$init .= $cfg;
		$init .= "dhx_sh = new dhtmlxSpreadSheet({\n\t\t\t";
		$init .= "load: cfg.load,\n\t\t\t";
		$init .= "save: cfg.save,\n\t\t\t";
		$init .= "parent: cfg.parent,\n\t\t\t";
		$init .= "autowidth: cfg.autowidth,\n\t\t\t";
		$init .= "autoheight: cfg.autoheight,\n\t\t\t";
		$init .= "skin: cfg.skin,\n\t\t\t";
		$init .= "math: cfg.math,\n\t\t\t";
		$init .= "icons_path: cfg['dhx_rel_path'] + 'imgs/icons/',\n\t\t\t";
		$init .= "image_path: cfg['dhx_rel_path'] + 'imgs/'\n\t\t";
		$init .= "});\n\t\t";
		$init .= "dhx_sh.load(cfg.sheet||'1', cfg.key||null);\n\t";
		$init .= "}, 1);\n";
		$init .= "}\n";
		if ($this->get('first_time')) {
			$init .= "dhtmlxEvent(window, 'load', onload_func_{$uid});";
			$this->set('first_time', false);
		} else
			$init .= "onload_func_{$uid}();";

		$html = "<div id='spreadsheet_box_{$uid}' style='width: {$this->get('width')}; height: {$this->get('height')}; background-color:white;'></div>\n\n";

		$result = $include."<script>".$init."</script>".$html;
		return $result;
	}

	public function uid() {
		return (string) rand(100000, 999999);
	}

}


?>