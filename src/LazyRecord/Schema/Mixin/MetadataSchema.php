<?php
namespace LazyRecord\Schema\Mixin;
use LazyRecord\Schema\MixinSchemaDeclare;
use LazyRecord\Schema;
use DateTime;

class MetadataSchema extends MixinSchemaDeclare
{
    public function schema()
    {
        $this->column('updated_on')
            ->timestamp()
            ->default(function() { 
                return date('Y-m-d H:i:s'); 
            })
            ->timestamp();

        $this->column('created_on')
            ->timestamp()
            ->default(function() { 
                return date('Y-m-d H:i:s'); 
            })
            ->timestamp();
    }

    // Mixin methods
    public static function getAge($record) 
    {
        $createdOn = $record->created_on;
        $currentDate = new DateTime;
        return $currentDate->diff($createdOn);
    }


}
