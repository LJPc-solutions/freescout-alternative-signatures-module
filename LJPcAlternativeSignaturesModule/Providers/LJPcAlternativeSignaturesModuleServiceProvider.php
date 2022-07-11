<?php

namespace Modules\LJPcAlternativeSignaturesModule\Providers;

use Config;
use Eventy;
use Illuminate\Support\ServiceProvider;
use Module;
use Modules\LJPcAlternativeSignaturesModule\Entities\MailboxCustomSignature;

class LJPcAlternativeSignaturesModuleServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->registerViews();
		$this->loadMigrationsFrom( __DIR__ . '/../Database/Migrations' );
		$this->hooks();
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews() {
		$viewPath = resource_path( 'views/modules/ljpcalternativesignaturesmodule' );

		$sourcePath = __DIR__ . '/../Resources/views';

		$this->publishes( [
			$sourcePath => $viewPath,
		], 'views' );

		$this->loadViewsFrom( array_merge( array_map( function ( $path ) {
			return $path . '/modules/ljpcalternativesignaturesmodule';
		}, Config::get( 'view.paths' ) ), [ $sourcePath ] ), 'signatures' );
	}

	/**
	 * Module hooks.
	 */
	public function hooks() {
		Eventy::addFilter( 'conversation.signature_processed', function ( $text, $conversation, $data, $escape ) {
			if ( $conversation->selected_signature !== null && $conversation->selected_signature !== 0 ) {
				$customSignature = MailboxCustomSignature::find( $conversation->selected_signature );
				if ( $customSignature !== null ) {
					return $conversation->replaceTextVars( $customSignature->content, $data, $escape );
				}
			}

			return $text;
		}, 10, 4 );

		Eventy::addAction( 'conversation.send_reply_save', function ( $conversation, $request ) {
			if ( ! empty( $request->selected_signature ) ) {
				$conversation->selected_signature = $request->selected_signature;
				$conversation->save();
			}
		}, 10, 2 );

		Eventy::addAction( 'mailboxes.settings_before_save', function ( $id, $request ) {
			$alternativeSignatureCount = $request->alternative_signature_count;
			if ( ! empty( $alternativeSignatureCount ) && (int) $alternativeSignatureCount > 0 ) {
				for ( $i = 0; $i < (int) $alternativeSignatureCount; $i ++ ) {
					$alternativeSignatureId      = "alternative_signature_id_$i";
					$alternativeSignatureId      = $request->$alternativeSignatureId;
					$alternativeSignatureAction  = "alternative_signature_action_$i";
					$alternativeSignatureAction  = $request->$alternativeSignatureAction;
					$alternativeSignatureTitle   = "alternative_signature_title_$i";
					$alternativeSignatureTitle   = $request->$alternativeSignatureTitle;
					$alternativeSignatureContent = "alternative_signature_content_$i";
					$alternativeSignatureContent = $request->$alternativeSignatureContent;

					if ( $alternativeSignatureAction === 'DELETE' || ( empty( $alternativeSignatureTitle ) && empty( $alternativeSignatureContent ) ) ) {
						if ( ! empty( $alternativeSignatureId ) && (int) $alternativeSignatureId > 0 ) {
							//Delete
							/** @var MailboxCustomSignature|null $customSignature */
							$customSignature = MailboxCustomSignature::find( $alternativeSignatureId );
							if ( $customSignature !== null ) {
								$customSignature->delete();
							}
						}
					} else {
						if ( empty( $alternativeSignatureTitle ) ) {
							$alternativeSignatureTitle = __( 'Alternative' ) . ' ' . ( $i + 1 );
						}
						if ( ! empty( $alternativeSignatureId ) && (int) $alternativeSignatureId > 0 ) {
							/** @var MailboxCustomSignature|null $customSignature */
							$customSignature = MailboxCustomSignature::find( $alternativeSignatureId );
							if ( $customSignature !== null ) {
								$customSignature->name    = $alternativeSignatureTitle;
								$customSignature->content = $alternativeSignatureContent;
								$customSignature->save();
							}
						} else {
							//Create
							$customSignature             = new MailboxCustomSignature();
							$customSignature->mailbox_id = $id;
							$customSignature->name       = $alternativeSignatureTitle;
							$customSignature->content    = $alternativeSignatureContent;
							$customSignature->save();
						}
					}
				}
			}
		}, 10, 2 );

		Eventy::addAction( 'conv_editor.editor_toolbar_prepend', function ( $mailbox, $conversation ) {
			//Blade render signature field.blade.php
			$customSignatures = MailboxCustomSignature::where( 'mailbox_id', $mailbox->id )->get();
			echo view( 'signatures::signature_field', [
				'mailbox'          => $mailbox,
				'conversation'     => $conversation,
				'customSignatures' => $customSignatures,
			] );
		}, 10, 2 );

		Eventy::addAction( 'mailbox.update.after_signature', function ( $mailbox ) {
			//Blade render update.blade.php
			$customSignatures = MailboxCustomSignature::where( 'mailbox_id', $mailbox->id )->get();
			echo view( 'signatures::update', [
				'mailbox'          => $mailbox,
				'customSignatures' => $customSignatures,
			] );
		} );

		Eventy::addFilter( 'javascripts', function ( $javascripts ) {
			$javascripts[] = Module::getPublicPath( 'ljpcalternativesignaturesmodule' ) . '/js/signatures.js';
			$javascripts[] = Module::getPublicPath( 'ljpcalternativesignaturesmodule' ) . '/js/laroute.js';

			return $javascripts;
		} );
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerTranslations();
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations() {
		$this->loadJsonTranslationsFrom( __DIR__ . '/../Resources/lang' );
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [];
	}
}
