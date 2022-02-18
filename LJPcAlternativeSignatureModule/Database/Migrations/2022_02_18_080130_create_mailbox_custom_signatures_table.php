<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailboxCustomSignaturesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'mailbox_custom_signatures', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer('mailbox_id');
            $table->text( 'name' );
            $table->text( 'content' );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'mailbox_custom_signatures' );
    }
}
