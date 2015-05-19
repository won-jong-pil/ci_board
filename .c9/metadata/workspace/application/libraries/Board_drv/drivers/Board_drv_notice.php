{"filter":false,"title":"Board_drv_notice.php","tooltip":"/application/libraries/Board_drv/drivers/Board_drv_notice.php","undoManager":{"stack":[[{"start":{"row":422,"column":56},"end":{"row":422,"column":57},"action":"insert","lines":["f"],"id":19}],[{"start":{"row":422,"column":57},"end":{"row":422,"column":58},"action":"insert","lines":["i"],"id":20}],[{"start":{"row":422,"column":58},"end":{"row":422,"column":59},"action":"insert","lines":["g"],"id":21}],[{"start":{"row":422,"column":74},"end":{"row":422,"column":85},"action":"remove","lines":["$board_info"],"id":22},{"start":{"row":422,"column":74},"end":{"row":422,"column":79},"action":"insert","lines":["$parm"]}],[{"start":{"row":472,"column":1},"end":{"row":472,"column":2},"action":"insert","lines":["\t"],"id":23}],[{"start":{"row":472,"column":2},"end":{"row":472,"column":3},"action":"insert","lines":["\t"],"id":24}],[{"start":{"row":472,"column":2},"end":{"row":472,"column":3},"action":"remove","lines":["\t"],"id":25}],[{"start":{"row":472,"column":2},"end":{"row":472,"column":82},"action":"insert","lines":["if($this->debug != 'N') $this->set_debug('get list config', json_encode($parm));"],"id":26}],[{"start":{"row":472,"column":62},"end":{"row":472,"column":80},"action":"remove","lines":["json_encode($parm)"],"id":27},{"start":{"row":472,"column":62},"end":{"row":472,"column":89},"action":"insert","lines":["$this->CI->db->last_query()"]}],[{"start":{"row":473,"column":0},"end":{"row":473,"column":124},"action":"remove","lines":["\t\tif($this->_debug == 'Y' ) $this->add_error($this->board_table_name.'_select_query', $this->CI->db->last_query(), 'debug');"],"id":28}],[{"start":{"row":473,"column":0},"end":{"row":474,"column":1},"action":"remove","lines":["","\t"],"id":29}],[{"start":{"row":487,"column":0},"end":{"row":493,"column":0},"action":"remove","lines":["\t","\t\tif( $this->_debug == 'Y' )","\t\t{","\t\t\t$this->add_error($this->board_table_name.'_select_data', json_encode($parm), 'debug');","\t\t}","\t",""],"id":30}],[{"start":{"row":486,"column":3},"end":{"row":487,"column":0},"action":"insert","lines":["",""],"id":31},{"start":{"row":487,"column":0},"end":{"row":487,"column":2},"action":"insert","lines":["\t\t"]}],[{"start":{"row":472,"column":53},"end":{"row":472,"column":59},"action":"remove","lines":["config"],"id":32},{"start":{"row":472,"column":53},"end":{"row":472,"column":54},"action":"insert","lines":["q"]}],[{"start":{"row":472,"column":54},"end":{"row":472,"column":55},"action":"insert","lines":["u"],"id":33}],[{"start":{"row":472,"column":55},"end":{"row":472,"column":56},"action":"insert","lines":["e"],"id":34}],[{"start":{"row":472,"column":56},"end":{"row":472,"column":57},"action":"insert","lines":["r"],"id":35}],[{"start":{"row":472,"column":57},"end":{"row":472,"column":58},"action":"insert","lines":["y"],"id":36}],[{"start":{"row":424,"column":48},"end":{"row":424,"column":61},"action":"remove","lines":["return FALSE;"],"id":37},{"start":{"row":424,"column":48},"end":{"row":424,"column":85},"action":"insert","lines":["throw new Exception('board_no_info');"]}],[{"start":{"row":424,"column":78},"end":{"row":424,"column":82},"action":"remove","lines":["info"],"id":38},{"start":{"row":424,"column":78},"end":{"row":424,"column":79},"action":"insert","lines":["t"]}],[{"start":{"row":424,"column":79},"end":{"row":424,"column":80},"action":"insert","lines":["a"],"id":39}],[{"start":{"row":424,"column":80},"end":{"row":424,"column":81},"action":"insert","lines":["b"],"id":40}],[{"start":{"row":424,"column":81},"end":{"row":424,"column":82},"action":"insert","lines":["l"],"id":41}],[{"start":{"row":424,"column":82},"end":{"row":424,"column":83},"action":"insert","lines":["e"],"id":42}],[{"start":{"row":424,"column":83},"end":{"row":424,"column":84},"action":"insert","lines":["_"],"id":43}],[{"start":{"row":424,"column":84},"end":{"row":424,"column":85},"action":"insert","lines":["ㅜ"],"id":44}],[{"start":{"row":424,"column":84},"end":{"row":424,"column":85},"action":"remove","lines":["ㅜ"],"id":50}],[{"start":{"row":424,"column":84},"end":{"row":424,"column":85},"action":"insert","lines":["n"],"id":51}],[{"start":{"row":424,"column":85},"end":{"row":424,"column":86},"action":"insert","lines":["a"],"id":52}],[{"start":{"row":424,"column":86},"end":{"row":424,"column":87},"action":"insert","lines":["m"],"id":53}],[{"start":{"row":424,"column":87},"end":{"row":424,"column":88},"action":"insert","lines":["e"],"id":54}],[{"start":{"row":98,"column":3},"end":{"row":98,"column":19},"action":"remove","lines":["$this->add_error"],"id":55},{"start":{"row":98,"column":3},"end":{"row":98,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":99,"column":3},"end":{"row":99,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":99,"column":3},"end":{"row":99,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":104,"column":3},"end":{"row":104,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":104,"column":3},"end":{"row":104,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":105,"column":3},"end":{"row":105,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":105,"column":3},"end":{"row":105,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":106,"column":3},"end":{"row":106,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":106,"column":3},"end":{"row":106,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":122,"column":29},"end":{"row":122,"column":45},"action":"remove","lines":["$this->add_error"]},{"start":{"row":122,"column":29},"end":{"row":122,"column":45},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":128,"column":6},"end":{"row":128,"column":22},"action":"remove","lines":["$this->add_error"]},{"start":{"row":128,"column":6},"end":{"row":128,"column":22},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":154,"column":8},"end":{"row":154,"column":24},"action":"remove","lines":["$this->add_error"]},{"start":{"row":154,"column":8},"end":{"row":154,"column":24},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":172,"column":7},"end":{"row":172,"column":23},"action":"remove","lines":["$this->add_error"]},{"start":{"row":172,"column":7},"end":{"row":172,"column":23},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":173,"column":7},"end":{"row":173,"column":23},"action":"remove","lines":["$this->add_error"]},{"start":{"row":173,"column":7},"end":{"row":173,"column":23},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":174,"column":7},"end":{"row":174,"column":23},"action":"remove","lines":["$this->add_error"]},{"start":{"row":174,"column":7},"end":{"row":174,"column":23},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":203,"column":3},"end":{"row":203,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":203,"column":3},"end":{"row":203,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":204,"column":3},"end":{"row":204,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":204,"column":3},"end":{"row":204,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":209,"column":3},"end":{"row":209,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":209,"column":3},"end":{"row":209,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":210,"column":3},"end":{"row":210,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":210,"column":3},"end":{"row":210,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":252,"column":6},"end":{"row":252,"column":22},"action":"remove","lines":["$this->add_error"]},{"start":{"row":252,"column":6},"end":{"row":252,"column":22},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":281,"column":9},"end":{"row":281,"column":25},"action":"remove","lines":["$this->add_error"]},{"start":{"row":281,"column":9},"end":{"row":281,"column":25},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":303,"column":8},"end":{"row":303,"column":24},"action":"remove","lines":["$this->add_error"]},{"start":{"row":303,"column":8},"end":{"row":303,"column":24},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":304,"column":8},"end":{"row":304,"column":24},"action":"remove","lines":["$this->add_error"]},{"start":{"row":304,"column":8},"end":{"row":304,"column":24},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":305,"column":38},"end":{"row":305,"column":54},"action":"remove","lines":["$this->add_error"]},{"start":{"row":305,"column":38},"end":{"row":305,"column":54},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":329,"column":9},"end":{"row":329,"column":25},"action":"remove","lines":["$this->add_error"]},{"start":{"row":329,"column":9},"end":{"row":329,"column":25},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":347,"column":8},"end":{"row":347,"column":24},"action":"remove","lines":["$this->add_error"]},{"start":{"row":347,"column":8},"end":{"row":347,"column":24},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":348,"column":8},"end":{"row":348,"column":24},"action":"remove","lines":["$this->add_error"]},{"start":{"row":348,"column":8},"end":{"row":348,"column":24},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":349,"column":38},"end":{"row":349,"column":54},"action":"remove","lines":["$this->add_error"]},{"start":{"row":349,"column":38},"end":{"row":349,"column":54},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":372,"column":3},"end":{"row":372,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":372,"column":3},"end":{"row":372,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":396,"column":6},"end":{"row":396,"column":22},"action":"remove","lines":["$this->add_error"]},{"start":{"row":396,"column":6},"end":{"row":396,"column":22},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":407,"column":3},"end":{"row":407,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":407,"column":3},"end":{"row":407,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":505,"column":3},"end":{"row":505,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":505,"column":3},"end":{"row":505,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":506,"column":3},"end":{"row":506,"column":19},"action":"remove","lines":["$this->add_error"]},{"start":{"row":506,"column":3},"end":{"row":506,"column":19},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":523,"column":4},"end":{"row":523,"column":20},"action":"remove","lines":["$this->add_error"]},{"start":{"row":523,"column":4},"end":{"row":523,"column":20},"action":"insert","lines":["$this->add_debug"]},{"start":{"row":557,"column":29},"end":{"row":557,"column":45},"action":"remove","lines":["$this->add_error"]},{"start":{"row":557,"column":29},"end":{"row":557,"column":45},"action":"insert","lines":["$this->add_debug"]}],[{"start":{"row":506,"column":89},"end":{"row":506,"column":98},"action":"remove","lines":[", 'debug'"],"id":56}],[{"start":{"row":505,"column":64},"end":{"row":505,"column":73},"action":"remove","lines":[", 'debug'"],"id":57}],[{"start":{"row":523,"column":96},"end":{"row":523,"column":105},"action":"remove","lines":[", 'debug'"],"id":58}],[{"start":{"row":557,"column":118},"end":{"row":557,"column":127},"action":"remove","lines":[", 'debug'"],"id":59}],[{"start":{"row":102,"column":5},"end":{"row":102,"column":18},"action":"remove","lines":["$this->_debug"],"id":60},{"start":{"row":102,"column":5},"end":{"row":102,"column":17},"action":"insert","lines":["$this->debug"]},{"start":{"row":122,"column":7},"end":{"row":122,"column":20},"action":"remove","lines":["$this->_debug"]},{"start":{"row":122,"column":7},"end":{"row":122,"column":19},"action":"insert","lines":["$this->debug"]},{"start":{"row":170,"column":9},"end":{"row":170,"column":22},"action":"remove","lines":["$this->_debug"]},{"start":{"row":170,"column":9},"end":{"row":170,"column":21},"action":"insert","lines":["$this->debug"]},{"start":{"row":207,"column":5},"end":{"row":207,"column":18},"action":"remove","lines":["$this->_debug"]},{"start":{"row":207,"column":5},"end":{"row":207,"column":17},"action":"insert","lines":["$this->debug"]},{"start":{"row":301,"column":10},"end":{"row":301,"column":23},"action":"remove","lines":["$this->_debug"]},{"start":{"row":301,"column":10},"end":{"row":301,"column":22},"action":"insert","lines":["$this->debug"]},{"start":{"row":345,"column":10},"end":{"row":345,"column":23},"action":"remove","lines":["$this->_debug"]},{"start":{"row":345,"column":10},"end":{"row":345,"column":22},"action":"insert","lines":["$this->debug"]},{"start":{"row":503,"column":6},"end":{"row":503,"column":19},"action":"remove","lines":["$this->_debug"]},{"start":{"row":503,"column":6},"end":{"row":503,"column":18},"action":"insert","lines":["$this->debug"]},{"start":{"row":521,"column":7},"end":{"row":521,"column":20},"action":"remove","lines":["$this->_debug"]},{"start":{"row":521,"column":7},"end":{"row":521,"column":19},"action":"insert","lines":["$this->debug"]},{"start":{"row":557,"column":6},"end":{"row":557,"column":19},"action":"remove","lines":["$this->_debug"]},{"start":{"row":557,"column":6},"end":{"row":557,"column":18},"action":"insert","lines":["$this->debug"]}],[{"start":{"row":521,"column":28},"end":{"row":522,"column":4},"action":"remove","lines":["","\t\t\t{"],"id":61}],[{"start":{"row":521,"column":28},"end":{"row":522,"column":0},"action":"remove","lines":["",""],"id":62}],[{"start":{"row":521,"column":28},"end":{"row":521,"column":29},"action":"remove","lines":["\t"],"id":63}],[{"start":{"row":521,"column":28},"end":{"row":521,"column":29},"action":"remove","lines":["\t"],"id":64}],[{"start":{"row":521,"column":28},"end":{"row":521,"column":29},"action":"remove","lines":["\t"],"id":65}],[{"start":{"row":521,"column":28},"end":{"row":521,"column":29},"action":"remove","lines":["\t"],"id":66}],[{"start":{"row":521,"column":28},"end":{"row":521,"column":29},"action":"insert","lines":[" "],"id":67}],[{"start":{"row":521,"column":123},"end":{"row":522,"column":4},"action":"remove","lines":["","\t\t\t}"],"id":68}],[{"start":{"row":396,"column":68},"end":{"row":396,"column":77},"action":"remove","lines":[", 'debug'"],"id":69}],[{"start":{"row":408,"column":91},"end":{"row":408,"column":96},"action":"remove","lines":[".'\\n'"],"id":70}],[{"start":{"row":408,"column":93},"end":{"row":409,"column":0},"action":"insert","lines":["",""],"id":71},{"start":{"row":409,"column":0},"end":{"row":409,"column":3},"action":"insert","lines":["\t\t\t"]}],[{"start":{"row":409,"column":3},"end":{"row":409,"column":4},"action":"insert","lines":["$"],"id":72}],[{"start":{"row":409,"column":4},"end":{"row":409,"column":5},"action":"insert","lines":["t"],"id":73}],[{"start":{"row":409,"column":5},"end":{"row":409,"column":6},"action":"insert","lines":["h"],"id":74}],[{"start":{"row":409,"column":5},"end":{"row":409,"column":6},"action":"remove","lines":["h"],"id":75}],[{"start":{"row":409,"column":4},"end":{"row":409,"column":5},"action":"remove","lines":["t"],"id":76}],[{"start":{"row":409,"column":3},"end":{"row":409,"column":4},"action":"remove","lines":["$"],"id":77}],[{"start":{"row":409,"column":3},"end":{"row":409,"column":4},"action":"insert","lines":["t"],"id":78}],[{"start":{"row":409,"column":4},"end":{"row":409,"column":5},"action":"insert","lines":["h"],"id":79}],[{"start":{"row":409,"column":5},"end":{"row":409,"column":6},"action":"insert","lines":["r"],"id":80}],[{"start":{"row":409,"column":6},"end":{"row":409,"column":7},"action":"insert","lines":["o"],"id":81}],[{"start":{"row":409,"column":7},"end":{"row":409,"column":8},"action":"insert","lines":["w"],"id":82}],[{"start":{"row":409,"column":8},"end":{"row":409,"column":9},"action":"insert","lines":[" "],"id":83}],[{"start":{"row":409,"column":9},"end":{"row":409,"column":10},"action":"insert","lines":["n"],"id":84}],[{"start":{"row":409,"column":10},"end":{"row":409,"column":11},"action":"insert","lines":["e"],"id":85}],[{"start":{"row":409,"column":11},"end":{"row":409,"column":12},"action":"insert","lines":["w"],"id":86}],[{"start":{"row":409,"column":12},"end":{"row":409,"column":13},"action":"insert","lines":[" "],"id":87}],[{"start":{"row":409,"column":13},"end":{"row":409,"column":14},"action":"insert","lines":["E"],"id":88}],[{"start":{"row":409,"column":14},"end":{"row":409,"column":15},"action":"insert","lines":["x"],"id":89}],[{"start":{"row":409,"column":15},"end":{"row":409,"column":16},"action":"insert","lines":["v"],"id":90}],[{"start":{"row":409,"column":16},"end":{"row":409,"column":17},"action":"insert","lines":["e"],"id":91}],[{"start":{"row":409,"column":16},"end":{"row":409,"column":17},"action":"remove","lines":["e"],"id":92}],[{"start":{"row":409,"column":15},"end":{"row":409,"column":16},"action":"remove","lines":["v"],"id":93}],[{"start":{"row":409,"column":15},"end":{"row":409,"column":16},"action":"insert","lines":["c"],"id":94}],[{"start":{"row":409,"column":16},"end":{"row":409,"column":17},"action":"insert","lines":["e"],"id":95}],[{"start":{"row":409,"column":17},"end":{"row":409,"column":18},"action":"insert","lines":["p"],"id":96}],[{"start":{"row":409,"column":18},"end":{"row":409,"column":19},"action":"insert","lines":["t"],"id":97}],[{"start":{"row":409,"column":19},"end":{"row":409,"column":20},"action":"insert","lines":["i"],"id":98}],[{"start":{"row":409,"column":20},"end":{"row":409,"column":21},"action":"insert","lines":["o"],"id":99}],[{"start":{"row":409,"column":21},"end":{"row":409,"column":22},"action":"insert","lines":["n"],"id":100}],[{"start":{"row":409,"column":22},"end":{"row":409,"column":24},"action":"insert","lines":["()"],"id":101}],[{"start":{"row":409,"column":24},"end":{"row":409,"column":25},"action":"insert","lines":["_"],"id":102}],[{"start":{"row":409,"column":24},"end":{"row":409,"column":25},"action":"remove","lines":["_"],"id":103}],[{"start":{"row":409,"column":24},"end":{"row":409,"column":25},"action":"insert","lines":[";"],"id":104}],[{"start":{"row":409,"column":23},"end":{"row":409,"column":25},"action":"insert","lines":["''"],"id":105}],[{"start":{"row":409,"column":24},"end":{"row":409,"column":25},"action":"insert","lines":["b"],"id":106}],[{"start":{"row":409,"column":25},"end":{"row":409,"column":26},"action":"insert","lines":["o"],"id":107}],[{"start":{"row":409,"column":26},"end":{"row":409,"column":27},"action":"insert","lines":["a"],"id":108}],[{"start":{"row":409,"column":27},"end":{"row":409,"column":28},"action":"insert","lines":["r"],"id":109}],[{"start":{"row":409,"column":28},"end":{"row":409,"column":29},"action":"insert","lines":["d"],"id":110}],[{"start":{"row":409,"column":29},"end":{"row":409,"column":30},"action":"insert","lines":["_"],"id":111}],[{"start":{"row":409,"column":30},"end":{"row":409,"column":31},"action":"insert","lines":["d"],"id":112}],[{"start":{"row":409,"column":31},"end":{"row":409,"column":32},"action":"insert","lines":["e"],"id":113}],[{"start":{"row":409,"column":32},"end":{"row":409,"column":33},"action":"insert","lines":["l"],"id":114}],[{"start":{"row":409,"column":33},"end":{"row":409,"column":34},"action":"insert","lines":["e"],"id":115}],[{"start":{"row":409,"column":34},"end":{"row":409,"column":35},"action":"insert","lines":["t"],"id":116}],[{"start":{"row":409,"column":35},"end":{"row":409,"column":36},"action":"insert","lines":["e"],"id":117}],[{"start":{"row":409,"column":36},"end":{"row":409,"column":37},"action":"insert","lines":["_"],"id":118}],[{"start":{"row":409,"column":37},"end":{"row":409,"column":38},"action":"insert","lines":["f"],"id":119}],[{"start":{"row":409,"column":38},"end":{"row":409,"column":39},"action":"insert","lines":["a"],"id":120}],[{"start":{"row":409,"column":39},"end":{"row":409,"column":40},"action":"insert","lines":["i"],"id":121}],[{"start":{"row":409,"column":40},"end":{"row":409,"column":41},"action":"insert","lines":["l"],"id":122}],[{"start":{"row":83,"column":45},"end":{"row":83,"column":46},"action":"insert","lines":["/"],"id":123}],[{"start":{"row":83,"column":46},"end":{"row":83,"column":47},"action":"insert","lines":["/"],"id":124}]],"mark":100,"position":100},"ace":{"folds":[],"scrolltop":1080,"scrollleft":0,"selection":{"start":{"row":83,"column":47},"end":{"row":83,"column":47},"isBackwards":true},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":{"row":76,"state":"php-start","mode":"ace/mode/php"}},"timestamp":1431995911834,"hash":"765f49ea94a103b4191a7288cadf8141f349539f"}