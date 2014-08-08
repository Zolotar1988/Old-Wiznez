<?php

/**
 (c) DHTMLX Ltd, 2011
 Licensing: You allowed to use this component for free under GPL or you need to obtain Commercial/Enterprise license to use it in non-GPL project
 Contact: sales@dhtmlx.com
 **/




require_once('db_common.php');
require_once('request.php');
require_once('math.php');
require_once('api.php');

class GridCellConnector {
	protected $connection;
	protected $request;
	protected $driver;
	protected $wrapper;
	protected $read_only = false;
	protected $math = null;
	protected $api = null;
	protected $db_prefix;

	public function __construct($connection, $db_prefix, $db_type = 'MySQL') {
		$this->connection = $connection;
		$this->db_prefix = $db_prefix;
		$this->request = new Request();
		$this->db_type = $db_type;
		$this->driver = new jsonDriver();
		$driver_name = $db_type.'DBDataWrapper';
		if (class_exists($driver_name))
			$this->wrapper = new $driver_name($connection, null);
		else
			throw new Exception("Data driver is not found");
		$this->math = new Math();
	}

	/*! render request
	 */
	public function render() {
		$edit = $this->request->get("edit");
		$sheet = $this->request->get("sheet");
		$this->api = new SpreadSheet($this->connection, $sheet, $this->db_prefix, $this->db_type);
		if ($edit == false)
			$this->get_data();
		else
			$this->save();
	}

	public function is_math() {
		return isset($_GET['dhx_math']);
	}

	/*! send data list to browser
	 */
	protected function get_data() {
		$sheet = $this->request->get("sheet");

		$config = $this->get_config(false, true);

		$read_only_mode = (!$this->check_key()) ? true : false;

		if ($this->request->get('sh_cfg') != false)
			$this->save_config();

		// getting data from database
		$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}data WHERE sheetid='".$this->e($sheet)."'");
		$data = Array();
		while ($item = $this->wrapper->get_next($res))
			$data[] = $item;

		// getting header from database
		$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}header WHERE sheetid='".$this->e($sheet)."'");
		$head = Array();
		while ($item = $this->wrapper->get_next($res))
			$head[] = $item;

		// getting ssheet config
		$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}sheet WHERE sheetid='".$this->e($sheet)."'");
		$sheet_obj = $this->wrapper->get_next($res);
		if ($sheet_obj == false)
			$res = $this->wrapper->query("INSERT INTO {$this->db_prefix}sheet (sheetid) VALUES ('".$this->e($sheet)."')");

		$out = $this->driver->start($sheet, $config, $read_only_mode);

		$heads = Array();
		$out .= $this->driver->headStart();
		for ($i = 0; $i < count($head); $i++)
			$heads[] = $this->driver->headToOut($head[$i]);
		$out .= implode($this->driver->separator(), $heads);
		$out .= $this->driver->headEnd();

		$out .= $this->driver->cellsStart();
		$cells = Array();
		for ($i = 0; $i < count($data); $i++) {
			$cell = $data[$i];
			if (!$this->is_math()) $cell['calc'] = $cell['data'];
			$cells[] = $this->driver->cellToOut($cell);
		}

		$out .= implode($this->driver->separator(), $cells);
		$out .= $this->driver->cellsEnd();

		$out = $this->driver->end($sheet);
		$this->out($out);
	}

	/*! save request processing
	 */
	protected function save() {
		if (!$this->check_key())
			$this->send_error("");
		$edit = $this->request->get("edit");
		switch ($edit) {
			case 'header':
				$this->save_header();
				break;
			default:
				$this->save_cell();
				break;
		}
	}

	/*! take config from request and save into database
	 */
	protected function save_config() {
		if (!$this->check_key())
			return false;

		$sheet = $this->request->get("sheet");
		$config = $this->request->get('sh_cfg');
		$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}sheet WHERE sheetid='".$this->e($sheet)."'");
		if ($this->wrapper->get_next($res)) {
			$res = $this->wrapper->query("UPDATE {$this->db_prefix}sheet SET cfg='".$this->e($config)."' WHERE sheetid='".$this->e($sheet)."'");
		} else {
			$this->wrapper->query("INSERT INTO {$this->db_prefix}sheet (sheetid, cfg) VALUES ('".$this->e($sheet)."', '".$this->e($config)."')");
		}
	}


	/*! process dataProcessor request and save cell
	 */
	protected function save_cell() {
		$sheet = $this->request->get("sheet");
		$rows = $this->request->get("rows");
		$cols = $this->request->get("cols");
		$values = $this->request->get("values");
		$styles = $this->request->get("styles");

		$cells = Array();
		for ($i = 0; $i < count($rows); $i++) {
			$cell = Array(
				"row" => $rows[$i],
				"col" => $cols[$i],
				"value" => $values[$i],
				"style" => $styles[$i]
			);
			$cells[] = $cell;
		}

		$responses = Array();
		for ($i = 0; $i < count($cells); $i++)
			$cells[$i] = $this->parseCellValue($cells[$i]);

		for ($i = 0; $i < count($cells); $i++)
			$responses = array_merge($responses, $this->save_one_cell($sheet, $cells[$i]));

		// sending response
		$response = $this->driver->get_start_response().join($this->driver->separator(), $responses).$this->driver->get_end_response();
		$this->out($response);
		return true;
	}

	protected function parseCellValue($cell) {
		if ((strlen($cell['value']) > 1)&&($cell['value'][0] == '=')) {
			$expr = $cell['value'];
			$expr = $this->replaceAreas($expr);
			$cell['parsed'] = $expr;
			$triggers = $this->getTriggers($expr);
		} else {
			$cell['parsed'] = $cell['value'];
			$triggers = array();
		}
		
		$coord = SpreadSheetCell::getColName($cell['col']).$cell['row'];
		$sheet = $this->request->get("sheet");
		$query = "DELETE FROM {$this->db_prefix}triggers WHERE `source`='".$this->e($coord)."' AND `sheetid`='".$this->e($sheet)."'";
		$this->wrapper->query($query);
		for ($i = 0; $i < count($triggers); $i++)
			$triggers[$i] = "('".$this->e($sheet)."', '".$this->e($triggers[$i])."', '".$this->e($coord)."')";
		$triggers = implode(", ", $triggers);
		if ($triggers) {
			$query = "INSERT INTO {$this->db_prefix}triggers (`sheetid`, `trigger`, `source`) VALUES {$triggers}";
			$this->wrapper->query($query);
		}

		return $cell;
	}

	protected function calculateValue($cell) {
		if ((strlen($cell['parsed']) > 1)&&($cell['parsed'][0] == '=')) {
			$expr = substr($cell['parsed'], 1);
			$subcell = $this->api->isCell($expr);
			if ($subcell !== false) {
				$expr = $subcell->getCalculatedValue();
			} else {
				$expr = $this->replaceCells($expr);
				$expr = $this->math->calculate($expr);
			}
			$cell['calc'] = $expr;
		} else {
			$cell['calc'] = $cell['parsed'];
		}
		return $cell;
	}


	public function replaceAreas($expr) {
		$expr = preg_replace_callback("/\\$?([A-Z]+)\\$?(\d+):\\$?([A-Z]+)\\$?(\d+)/i", Array($this, "replaceAreasCallback"), $expr);
		return $expr;
	}

	public function replaceAreasCallback($matches) {
		$c1_col = SpreadSheetCell::getColIndex($matches[1]);
		$c1_row = (int) $matches[2];
		$c2_col = SpreadSheetCell::getColIndex($matches[3]);
		$c2_row = (int) $matches[4];
		
		if ($c1_col > $c2_col)
			list($c2_col, $c1_col) = array($c1_col, $c2_col);
		
		if ($c1_row > $c2_row)
			list($c2_row, $c1_row) = array($c1_row, $c2_row);


		$diap = Array();
		for ($i = $c1_row; $i <= $c2_row; $i ++) {
			for ($j = $c1_col; $j <= $c2_col; $j++) {
				$diap[] = SpreadSheetCell::getColName($j).$i;
			}
		}
		return implode(";", $diap);
	}

	public function getTriggers($expr) {
		$expr = preg_match_all("/([A-Z]+\d+)[^\(]?/i", $expr, $matches);
		$triggers = Array();
		for ($i = 0; $i < count($matches[1]); $i++)
			$triggers[] = $matches[1][$i];
		return $triggers;
	}


	public function replaceCells($expr) {
		$expr = preg_replace_callback("/(\\$?[A-Z]+\\$?\d+)([^\(]?)/i", Array($this, "replaceCellsCallback"), $expr);
		return $expr;
	}

	public function replaceCellsCallback($matches) {
		// LOG10 is a function, not a cell!
		if ($matches[0] == 'LOG10') return 'LOG10';
		$coord = str_replace(':', '', $matches[1]);
		$coord = str_replace('$', '', $matches[1]);
		$cell = $this->api->getCell($coord);
		$value = $cell->getCalculatedValue();
		if ($value === null) $value = '0';
		return $value.$matches[2];
	}

	protected function save_one_cell($sheet, $cell, $processed = Array()) {
		$coord = SpreadSheetCell::getColName($cell['col']).$cell['row'];
		if ($this->is_math()) {
			if (isset($processed[$coord]))
				$cell['calc'] = '#CIRC_REFERENCE';
			else
				$cell = $this->calculateValue($cell);
		} else
			$cell['calc'] = $cell['value'];
		/*! selection action type
		 */
		if (($cell['value'] == '')&&($cell['style'] == ''))
			// delete cell from database if its value is empty
			$status = "deleted";
		else {
			// if the same cell already save in DB we have to update it, have to insert it otherwise
			$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}data WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($cell['col'])."' AND rowid='".$this->e($cell['row'])."'");
			$result = $this->wrapper->get_next($res);
			$status = ($result != false) ? "updated" : "inserted";
		}
		// action running
		switch ($status) {
			case 'inserted':
				$res = $this->wrapper->query("INSERT INTO {$this->db_prefix}data (sheetid, columnid, rowid, data, style, parsed, calc) VALUES ('".$this->e($sheet)."', ".$this->e($cell['col']).", ".$this->e($cell['row']).", '".$this->e($cell['value'])."', '".$this->e($cell['style'])."', '".$this->e($cell['parsed'])."', '".$this->e($cell['calc'])."')");
				break;
			case 'updated':
				$res = $this->wrapper->query("UPDATE {$this->db_prefix}data SET data='".$this->e($cell['value'])."', parsed='".$this->e($cell['parsed'])."', calc='".$this->e($cell['calc'])."', style='".$this->e($cell['style'])."' WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($cell['col'])."' AND rowid='".$this->e($cell['row'])."'");
				break;
			case 'deleted':
				$res = $this->wrapper->query("DELETE FROM {$this->db_prefix}data WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($cell['col'])."' AND rowid='".$this->e($cell['row'])."'");
				break;
		}
		$response = Array();
		if (!$this->is_math()) $cell['calc'] = $cell['value'];
		if ($res) {
			$response[] = $this->driver->success_response($cell['row'], $cell['col'], $cell['value'], $cell['calc']);
		} else
			$response[] = $this->driver->error_response($cell['row'], $cell['col']);

		if ($cell['calc'] === '#CIRC_REFERENCE') return $response;
		$processed[$coord] = true;
		$query = "SELECT * FROM {$this->db_prefix}triggers WHERE `trigger`='".$this->e($coord)."' AND `sheetid`='".$this->e($sheet)."'";
		$result = $this->wrapper->query($query);
		$triggers = Array();
		while ($trigger = $this->wrapper->get_next($result))
			$triggers[] = $trigger['source'];

		for ($i = 0; $i < count($triggers); $i++) {
			$coords = SpreadSheetCell::parse_coord($triggers[$i]);
			$query = "SELECT * FROM {$this->db_prefix}data WHERE `sheetid`='".$this->e($sheet)."' AND `rowid`='".$this->e($coords['row'])."' AND `columnid`=".$this->e($coords['col'])."";
			$res = $this->wrapper->query($query);
			$obj = $this->wrapper->get_next($res);
			$cell = Array(
				"row" => $obj['rowid'],
				"col" => $obj['columnid'],
				"value" => $obj['data'],
				"style" => $obj['style'],
				"parsed" => $obj['parsed']
			);
			$response = array_merge($response, $this->save_one_cell($sheet, $cell, $processed));
		}

		return $response;
	}


	// send success response
	protected function send_success($row, $col,$name) {
		$response = $this->driver->get_start_response().$this->driver->success_response($row, $col, $name, $name).$this->driver->get_end_response();
		$this->out($response);
	}

	// send error response
	protected function send_error($row, $col) {
		$response = $this->driver->get_start_response().$this->driver->error_response($row, $col).$this->driver->get_end_response();
		$this->out($response);
	}

	// save header parameters
	protected function save_header() {
		$sheet = $this->request->get("sheet");
		$col = $this->request->get("col");
		$name = $this->request->get("name");
		$width = $this->request->get("width");
		$width = ($width == "false") ? 50 : $width;
		// detects action type
		$res = $this->wrapper->query("SELECT * FROM {$this->db_prefix}header WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($col)."'");
		$result = $this->wrapper->get_next($res);
		$status = ($result != false) ? "updated" : "inserted";
		// action run
		switch ($status) {
			case 'inserted':
				$res = $this->wrapper->query("INSERT INTO {$this->db_prefix}header VALUES ('".$this->e($sheet)."', ".$this->e($col).", '".$this->e($name)."', ".$this->e($width).")");
				break;
			case 'updated':
				$res = $this->wrapper->query("UPDATE {$this->db_prefix}header SET label='".$this->e($name)."', width=".$this->e($width)." WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($col)."'");
				break;
			case 'deleted':
				$res = $this->wrapper->query("DELETE FROM {$this->db_prefix}header WHERE sheetid='".$this->e($sheet)."' AND columnid='".$this->e($col)."'");
				break;
		}
		// send response
		if ($res)
			$this->send_success("", $col, $name);
		else {
			$this->send_error("", $col);
		}
	}

	/*! sets read only mode
	 *	@param read_only
	 *		read only mode is turned on if it's true or turned off otherwise
	 */
	public function set_read_only($read_only) {
		$this->read_only = $read_only;
	}

	/*! check if key given in request
	 * @return
	 *		true if key is correct or false otherwise
	 */
	protected function check_key() {
		if ($this->read_only == true) return false;
		$key = $this->request->get('key');
		$sheet = $this->request->get('sheet');
		$res = $this->wrapper->query("SELECT `key` FROM {$this->db_prefix}sheet WHERE sheetid='".$this->e($sheet)."'");
		$sheetkey = $this->wrapper->get_next($res);
		$sheetkey = $sheetkey['key'];
		if (($sheetkey == null)||($sheetkey == $key))
			return true;
		else
			return false;
	}

	/*! send response and stop script running
	 */
	protected function out($result, $dont_die = false) {
		$this->driver->header();
		echo $this->request->get('jsonp').$result;
		if (!$dont_die) die();
	}

	public function enable_log($filename = 'sh_log.txt') {
		LogMaster::enable_log($filename);
	}


	protected function unserialize_config($config_str) {
		$cfg = explode(";", $config_str);
		$result = Array();
		for ($i = 0; $i < count($cfg); $i++) {
			$option = explode(':', $cfg[$i]);
			if (count($option) == 2)
				$result[$option[0]] = $option[1];
		}
		return $result;
	}

	protected function get_config($sheet = false, $serialization = false) {
		$sheet = ($sheet !== false) ? $sheet : $this->request->get('sheet');
		$config = $this->request->get('sh_cfg');
		if ($config == false) {
			$res = $this->wrapper->query("SELECT cfg FROM {$this->db_prefix}sheet WHERE sheetid='".$this->e($sheet)."'");
			if ($res !== false)
				$item = $this->wrapper->get_next($res);
			else
				$item = Array('cfg' => '');
			$config = (isset($item['cfg'])) ? $item['cfg'] : '';
		}
		if ($serialization == false)
			$config = $this->unserialize_config($config);
		return $config;
	}

	protected function e($str) {
		return $this->wrapper->escape($str);
	}
}

class xmlDriver {

	public function start($sheet, $config, $read_only = false) {
		$start = "<data sheetid='{$sheet}'";
		$start .= " config='{$config}'";
		$start .= ($read_only == true) ? " readonly='true'" : "";
		$start .= ">";
		return $start;
	}

	public function end() {
		return "</data>";
	}

	public function cellsStart() {
		return "";
	}

	public function cellsEnd() {
		return "";
	}

	public function cellToOut($obj) {
		$result = "<cell row='{$obj['rowid']}' col='{$obj['columnid']}'";
		$result .= ($obj['style'] != '') ? " style='{$obj['style']}'" : "";
		$result .= "><![CDATA[{$obj['data']}]]></cell>";
		return $result;
	}

	public function headStart() {
		return "";
	}

	public function headEnd() {
		return "";
	}

	public function headToOut($obj) {
		$result = "<head col='{$obj['columnid']}' width='{$obj['width']}'><![CDATA[{$obj['label']}]]></head>";
		return $result;
	}

	public function header() {
		header('Content-type: text/xml');
	}

	public function get_start_response() {
		return '<data>';
	}

	public function get_end_response() {
		return '</data>';
	}

	public function error_response($row, $col) {
		return "<action row=\"{$row}\" col=\"{$col}\" type=\"error\" />";
	}

	public function success_response($row, $col) {
		return "<action row=\"{$row}\" col=\"{$col}\" type=\"updated\" />";
	}
}


class jsonDriver {

	protected $result = array();
	
	public function start($sheet, $config, $read_only = false) {
		$this->result["sheetid"] = $sheet;
		$this->result["config"] = $config;
		$this->result["readonly"] = ($read_only == true) ? true : false;
		return "";
	}

	public function end() {
		return json_encode($this->result);
	}

	public function cellsStart() {
		$this->result["cells"] = array();
		return "";
	}

	public function cellsEnd() {
		return "";
	}

	public function cellToOut($obj) {
		$cell = array(
			"row" => $obj["rowid"],
			"col" => $obj["columnid"],
			"text" => $obj["data"],
			"calc" => $obj["calc"]
		);
		if ($obj["style"] !== "") $cell["style"] = $obj["style"];
		$this->result["cells"][] = $cell;
		return "";
	}

	public function headStart() {
		$this->result["head"] = array();
		return "";
	}

	public function headEnd() {
		return "";
	}

	public function headToOut($obj) {
		$cell = array(
			"col" => $obj["columnid"],
			"width" => $obj["width"],
			"label" => $obj["label"]
		);
		$this->result["head"][] = $cell;
		return "";
	}

	public function header() {
		header('Content-type: application/json');
	}

	public function get_start_response() {
		return '[';
	}

	public function get_end_response() {
		return ']';
	}

	public function error_response($row, $col) {
		$result = array(
			"row" => $row,
			"col" => $col,
			"type" => "error"
		);
		return json_encode($result);
	}

	public function success_response($row, $col, $value, $calc) {
		$result = array(
			"row" => $row,
			"col" => $col,
			"text" => $value,
			"calc" => $calc,
			"type" => 'updated'
		);
		return json_encode($result);
	}

	public function separator() {
		return ",";
	}
}

?>