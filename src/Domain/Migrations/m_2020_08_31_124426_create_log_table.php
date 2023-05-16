<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use Untek\Database\Migration\Domain\Base\BaseCreateTableMigration;

class m_2020_08_31_124426_create_log_table extends BaseCreateTableMigration
{

    protected $tableName = 'log_history';
    protected $tableComment = 'История логирования';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('level')->comment('Тип');
            $table->string('level_name')->nullable()->comment('Имя типа');
            $table->string('channel')->nullable()->comment('Канал логгера');
            $table->text('message')->nullable()->comment('Текст сообщения');
            $table->text('context')->nullable()->comment('Контекст');
            $table->text('extra')->nullable()->comment('Различные параметры');
            $table->timestamp('created_at')->comment('Время создания');

            $table->index(['level']);
            $table->index(['channel']);
        };
    }
}
