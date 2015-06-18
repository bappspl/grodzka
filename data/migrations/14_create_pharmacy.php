<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePharmacy extends AbstractMigration
{

    public function up()
    {
        $this->table('pharmacy', array())
            ->addColumn('name', 'string')
            ->addColumn('city', 'string')
            ->addColumn('street', 'string')
            ->addColumn('phone', 'string')
            ->addColumn('weekdays', 'string')
            ->addColumn('saturday', 'string')
            ->addColumn('sunday', 'string')
            ->addColumn('zip_code', 'string')
            ->addColumn('place_id', 'integer')
            ->addForeignKey('place_id', 'cms_place', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();
    }

    public function down()
    {
        $this->dropTable('pharmacy');
    }
}