<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taskDetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id');
			$table->string('status', 45);
			$table->string('remarks', 255);
			$table->integer('assignee_id');
			$table->string('assignee', 255);
			$table->integer('daysOfAction');
			$table->integer('doc_id')->references('id')->on('purchase_request');
			$table->dateTime('otherDate');
			$table->dateTime('dateFinished');
			$table->dateTime('dateReceived');
			$table->dateTime('dueDate');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	
	public function down()
	{
		Schema::drop('taskDetails');
	}

	
}
