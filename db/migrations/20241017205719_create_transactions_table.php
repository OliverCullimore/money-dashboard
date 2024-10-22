<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTransactionsTable extends AbstractMigration
{
  /**
   * Change Method.
   *
   * Write your reversible migrations using this method.
   *
   * More information on writing migrations is available here:
   * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
   *
   * Remember to call "create()" or "update()" and NOT "save()" when working
   * with the Table class.
   */
  public function change(): void
  {
    // create the table
    $table = $this->table('transactions', ['id' => 'transaction_id']);
    $table->addColumn('account_id', 'integer', ['limit' => 11])
      ->addColumn('external_id', 'string', ['limit' => 255])
      ->addColumn('description', 'text')
      ->addColumn('value', 'float')
      ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
      ->addColumn('updated_at', 'datetime')
      ->create();
  }
}
