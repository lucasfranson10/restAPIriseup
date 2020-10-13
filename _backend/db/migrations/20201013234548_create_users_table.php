<?php
declare(strict_types=1);

use \Migration\Migration;

final class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('user', function (Illuminate\Database\Schema\Blueprint $table) {
            // Auto-increment id
            $table->increments('user_id');
            $table->string('user_name',50);
            $table->string('user_email',50)->unique();
            $table->string('user_prof',50);
            $table->string('user_exp',50);
            $table->string('user_phone',50);
            $table->string('user_loc',50);
            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()
    {
        $this->schema->drop('users');
    }
}
