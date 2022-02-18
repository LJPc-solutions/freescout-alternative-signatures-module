<?php
namespace Modules\LJPcAlternativeSignaturesModule\Entities;

use Illuminate\Database\Eloquent\Model;

class MailboxCustomSignature extends Model {
	protected $table = 'mailbox_custom_signatures';
	protected $fillable = [
		'mailbox_id',
		'name',
		'content',
	];
}
