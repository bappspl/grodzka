<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMedicaments extends AbstractMigration
{

    public function up()
    {
        $this->table('medicaments', array())
            ->addColumn('name', 'string')
            ->addColumn('description', 'text', array('null' => true))
            ->save();
    }

    public function down()
    {
        $this->dropTable('medicaments');
    }
}