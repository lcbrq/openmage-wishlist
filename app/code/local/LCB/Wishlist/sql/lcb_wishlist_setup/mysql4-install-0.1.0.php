<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('lcb_wishlist');
if ($installer->getConnection()->isTableExists($tableName) != true) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn(
            'id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'entity Id'
        )
        ->addColumn(
            'customer_id',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'customer id'
        )
        ->addColumn(
            'customer_group',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'customer group'
        )
        ->addColumn(
            'item_id',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'item id'
        )
        ->addColumn(
            'item_group',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'item group'
        )
        ->addColumn(
            'item_subgroup',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'item subgroup'
        )
        ->addColumn(
            'item_path',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(),
            'item path'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'created at'
        );

    $installer->getConnection()->createTable($table);
}

$installer->endSetup();
