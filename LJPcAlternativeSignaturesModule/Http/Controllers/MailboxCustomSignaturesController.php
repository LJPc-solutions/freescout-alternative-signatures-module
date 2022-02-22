<?php

namespace Modules\LJPcAlternativeSignaturesModule\Http\Controllers;

use App\Conversation;
use App\Mailbox;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\LJPcAlternativeSignaturesModule\Entities\MailboxCustomSignature;

class MailboxCustomSignaturesController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function get( $id, $signatureId ) {
		$mailbox = Mailbox::findOrFail( $id );

		$conversation          = new Conversation(); //To be able to replace vars
		$conversation->mailbox = $mailbox;

		if ( (int) $signatureId === 0 ) {
			$mailbox = Mailbox::find( $id );
			if ( $mailbox === null ) {
				return Response::json( [], 404 );
			}

			return Response::json( [
				'id'         => 0,
				'mailbox_id' => $id,
				'name'       => 'Default',
				'content'    => $conversation->replaceTextVars( $mailbox->signature, [], true ),
			], 200 );
		}

		$signature          = MailboxCustomSignature::find( (int) $signatureId );
		$signature->content = $conversation->replaceTextVars( $signature->content, [], true );

		return Response::json( $signature, 200 );
	}
}
