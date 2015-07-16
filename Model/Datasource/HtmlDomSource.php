<?php
App::uses('DataSource', 'Model/Datasource');
App::import('Vendor','simple_html_dom',array('file'=>'sunra/php-simple-html-dom-parser/src/sunra/PhpSimple/simplehtmldom_1_5'));
/**
 * Html Dom Datasource
 *
 * Datasource for array based models
 */
class HtmlDomSource extends DataSource {
/**
 * Description string for this Data Source.
 *
 * @var string
 */
	public $description = 'Array Datasource';

	protected $_baseConfig = array(
		'driver' => '' // Just to avoid DebugKit warning
	);
/**
*source html to parse
*
* @var string
*/
protected $source;

/**
* simple dom instance
*/
protected $Dom;

/**
 * Returns a Model description (metadata) or null if none found.
 * not supported
 * @param Model $model
 */
	public function describe($model) {
		return false;
	}
/**
 * List sources
 *
 * @param mixed $data
 * @return boolean Always false. It's not supported
 */
	public function listSources($data = null) {
		return false;
	}
/**
* construct
*/
public function __construct($config = array()) 
{
$config = array_merge(array('array_filter_callback'=>null,'source'=> null),$config);
parent::__construct($config);

if(!empty($config['source'])) {
    $this->source = (string) $config['source'];
}

}
/**
* loads dom contents
* @param object $model model datasource is attached to
 * @return mixed
 */
private function loadDom($model) {
    if(!empty($model->source)) {
    	$this->source = $model->source;
    }
   if(file_exists($this->source)) {
   	   $str = file_get_contents($this->source);
   	   return  str_get_html($str);
   }
   return false;
}
/**
 * Used to read records from the Datasource. The "R" in CRUD
 *
 * @param Model $model The model being read.
 * @param array $queryData An array of query data used to find the data you want
 * @param null $recursive
 * @return mixed
 */
	public function read(Model $model, $queryData = array(), $recursive = null) {
		$this->Dom = $this->loadDom($model);
        $data = array();
		$fields = $queryData['fields'];
		unset($queryData['fields']);

		if(count($fields) && $fields !== 'COUNT') {
                extract($queryData);
             $data = $this->extractData($model ,$fields , $conditions);
		}
		 return $data;
	}

/**
* extracts data from source
* @param $model 
* @param $fields
* @return array
*/
 public function extractData($model, $fields = array() , $conditions) {

 	              $result = array();
                  foreach ($fields as  $field) {

                  	$_selector = $conditions[$field] ;
                  	if(strpos($_selector, ' ') !== false) {

                  		$selector = explode(' ', $_selector);
                       $index = $selector[count($selector)-1];

                  		if(ctype_digit(trim($index)) {

                        unset($selector[count($selector)-1]);
                        $elem = $this->Dom->find(implode(' ',$selector), (int)$index);
                        $result[$model->alias][$field] = $elem->innertext;
                  	   }

                       else {
                             //default
                             $result[$model->alias][$field] = $this->Dom->find($_selector);
                           }
                    }
                  	else {
                        //default
                  	    $result[$model->alias][$field] = $this->Dom->find($_selector);
                  	 }
                  
                  if(is_array($result[$model->alias][$field]) && $this->config['array_filter_callback']) {
                  	     if(!method_exists($model, $this->config['array_filter_callback'])){

                  	     	throw new CakeException(sprintf('No "%s" method in the "%s" Model.', $this->config['array_filter_callback'], $model));
                  	     }
                  	       $callback = $this->config['array_filter_callback'];
                  	        $result[$model->alias][$field] = call_user_func_array(array($model, $callback), $result[$model->alias][$field]);
                  }

                  else {
                        if(is_array($result[$model->alias][$field]) {
                        	foreach ($result[$model->alias][$field] as  $node) {

                        		$result[$model->alias][$field][] = $node->innertext;
                        	}
                        }
                  }
                  }
                  $this->dom = null;
                  return $result;
}