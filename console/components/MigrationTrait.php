<?php


namespace console\components;


trait MigrationTrait
{
    public function getTableOptions()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    public function processTableName($name)
    {
        return '{{%'.$name.'}}';
    }
} 