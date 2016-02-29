<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    use \console\components\MigrationTrait;

    public function up()
    {

        $this->createTable($this->processTableName('user'), [
            'id'                    => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'username'              => $this->string()->notNull()->unique(),
            'auth_key'              => $this->string(32)->notNull(),
            'password_hash'         => $this->string()->notNull(),
            'password_reset_token'  => $this->string()->unique(),
            'email'                 => $this->string()->notNull()->unique(),
            'status'                => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'            => $this->timestamp()->notNull(),
            'updated_at'            => $this->timestamp()->notNull(),
            'photo'                 => $this->string(1024),
            'rating'                => $this->integer()->defaultValue(0),
            'rating_votes_col'      => $this->integer()->unsigned()->defaultValue(0),
        ], $this->getTableOptions());

        $this->createTable($this->processTableName('callboard'), [
            'id'         => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'title'      =>'VARCHAR(200) NOT NULL',
            'text'       =>'TEXT(2000) NOT NULL',
            'image'      =>'VARCHAR(2000) NOT NULL',
            'user_id'    =>'INT(11) UNSIGNED NOT NULL',
            'active'     =>'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at' =>'TIMESTAMP NOT NULL',
            'updated_at' =>'TIMESTAMP NOT NULL',
            'INDEX `fk_callboard_user_idx` (`user_id` ASC)',
        ]);
        $this->addForeignKey('fk_callboard_user', $this->processTableName('callboard'), 'user_id', $this->processTableName('user'), 'id');

        $this->createTable($this->processTableName('comment'), [
            'id'                => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'text'              => 'TEXT(2000) NOT NULL',
            'created_at'        => 'TIMESTAMP NOT NULL',
            'user_id'           => 'INT(11) UNSIGNED NOT NULL',
            'user_comment_id'   => 'INT(11) UNSIGNED NOT NULL',
            'INDEX `fk_comment_user_idx`  (`user_id` ASC)',
            'INDEX `fk_comment_user1_idx` (`user_comment_id` ASC)',
        ]);
        $this->addForeignKey('fk_comment_user',     $this->processTableName('comment'), 'user_id',          $this->processTableName('user'), 'id');
        $this->addForeignKey('fk_comment_user1',    $this->processTableName('comment'), 'user_comment_id',  $this->processTableName('user'), 'id');

        $this->createTable($this->processTableName('rating_vote_user'), [
            'id'            => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'user_id'       => 'INT(11) UNSIGNED NOT NULL',
            'user_vote_id'  => 'INT(11) UNSIGNED NOT NULL',
            'created_at'    => 'TIMESTAMP NOT NULL',
            'num'           => 'TINYINT(10) NOT NULL',
            'INDEX `fk_rating_user_idx`      (`user_id` ASC)',
            'INDEX `fk_rating_vote_user_idx` (`user_vote_id` ASC)',
        ]);
        $this->addForeignKey('fk_rating_user',      $this->processTableName('rating_vote_user'), 'user_id',         $this->processTableName('user'), 'id');
        $this->addForeignKey('fk_rating_vote_user', $this->processTableName('rating_vote_user'), 'user_vote_id',    $this->processTableName('user'), 'id');


    }

    public function down()
    {
        $this->dropTable($this->processTableName('rating_vote_user'));
        $this->dropTable($this->processTableName('comment'));
        $this->dropTable($this->processTableName('callboard'));
        $this->dropTable($this->processTableName('user'));
    }
}
