# DomSource Plugin
a cakephp datasource for scrapping html Dom source file. it supports jquery style selectors for Dom manuplation in model find conditions. it works by taking querydata fields as schema and keys return results

# installation

    $ composer require cholthi/html_dom_source:"*"
or with git

    git clone https://github.com/cholthi/DomSource.git
    
# configuration
you can configure the datasource in database.php or in the model directly

    //database.php
    $domSource = array(
        'array_filter_callback' = 'yourCallback',//optional
        'source' => 'your html file url'//required
        );
or in the model

    ExampleModel.php
    $source = 'your file';
    
# Examples 

    //controller
    public function scrap() {
    $fields = array('title' ,'articles');
    $conditions = array('title'=> 'h1.title 0, 'articles'= 'div div span.body'); //see simplehtmldom.sourceforge.net for more selectors
      $data =  $this->{$this->modelClass}->find('all', compact(fields, conditions);
       debug($data);
       }

