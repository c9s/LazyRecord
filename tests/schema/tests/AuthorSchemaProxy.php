<?php
namespace tests;

use Lazy\Schema;

class AuthorSchemaProxy extends Schema
{

    public function __construct()
    {
        /** columns might have closure, so it can not be const */
        $this->columns     = array( 
  'created_on' => array( 
      'name' => 'created_on',
      'attributes' => array( 
          'isa' => 'DateTime',
          'defaultBuilder' => function() { return date('c'); },
        ),
    ),
  'id' => array( 
      'name' => 'id',
      'attributes' => array( 
          'isa' => 'int',
          'primary' => true,
        ),
    ),
  'name' => array( 
      'name' => 'name',
      'attributes' => array( 
          'isa' => 'str',
          'type' => 'varchar(128)',
        ),
    ),
  'email' => array( 
      'name' => 'email',
      'attributes' => array( 
          'isa' => 'str',
          'required' => true,
          'type' => 'varchar(128)',
        ),
    ),
  'identity' => array( 
      'name' => 'identity',
      'attributes' => array( 
          'isa' => 'str',
          'unique' => true,
          'required' => true,
          'type' => 'varchar(128)',
        ),
    ),
  'confirmed' => array( 
      'name' => 'confirmed',
      'attributes' => array( 
          'isa' => 'bool',
          'default' => false,
        ),
    ),
);
        $this->columnNames = array( 
  'name',
  'email',
  'identity',
  'confirmed',
);
        $this->primaryKey  =  'id';
        $this->table       = 'authors';
        $this->modelClass  = 'tests\\Author';
        $this->label       = 'Author';
    }

}
