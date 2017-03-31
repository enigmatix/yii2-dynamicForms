<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%dynamic_form}}`.
 */
class m170331_014450_create_table_dynamic_form extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%dynamic_form}}', [

            'id' => $this->primaryKey()->unsigned()->notNull(),
            'uuid' => $this->string(255),
            'form_object' => $this->string(255),
            'form_data' => $this->text(),
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
            'owned_by' => $this->integer(11)->unsigned(),
            'created_by' => $this->integer(11)->unsigned(),
            'updated_by' => $this->integer(11)->unsigned(),

        ]);
 
        // creates index for column `created_by`
        $this->createIndex(
            'dynamic_form_ibfk_1',
            '{{%dynamic_form}}',
            'created_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'dynamic_form_ibfk_1',
            '{{%dynamic_form}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `owned_by`
        $this->createIndex(
            'dynamic_form_ibfk_2',
            '{{%dynamic_form}}',
            'owned_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'dynamic_form_ibfk_2',
            '{{%dynamic_form}}',
            'owned_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            'dynamic_form_ibfk_3',
            '{{%dynamic_form}}',
            'updated_by'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'dynamic_form_ibfk_3',
            '{{%dynamic_form}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'dynamic_form_ibfk_1',
            '{{%dynamic_form}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'dynamic_form_ibfk_1',
            '{{%dynamic_form}}'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'dynamic_form_ibfk_2',
            '{{%dynamic_form}}'
        );

        // drops index for column `owned_by`
        $this->dropIndex(
            'dynamic_form_ibfk_2',
            '{{%dynamic_form}}'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'dynamic_form_ibfk_3',
            '{{%dynamic_form}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            'dynamic_form_ibfk_3',
            '{{%dynamic_form}}'
        );

        $this->dropTable('{{%dynamic_form}}');
    }
}
