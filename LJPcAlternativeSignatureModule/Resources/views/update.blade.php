@if (Auth::user()->can('updateSettings', $mailbox) || Auth::user()->can('updateEmailSignature', $mailbox))
    <div class="alternative-signatures">
        <div class="alternative-signature-template hidden">
            <div class="form-group alternative-signature-group-$index">
                <input type="hidden" class="alternative-signature-action" name="alternative_signature_action_$index" value="KEEP">
                <label for="alternative-signature-$index" class="col-sm-2 control-label">{{ __('Alternative Email Signature') }} $iteration<br/>
                    <span class="text-danger delete-alternative-signature" style="cursor: pointer;">{{ __('Delete') }}</span>
                    <span class="text-success restore-alternative-signature hidden" style="cursor: pointer;">{{ __('Restore') }}</span>
                </label>
                <div class="col-md-9 signature-editor">
                    <strong>{{__('Name')}}</strong><br/>
                    <input type="text" class="form-control" name="alternative_signature_title_$index"><br/>
                    <strong>{{__('Content')}}</strong><br/>
                    <textarea id="alternative-signature-$index" class="form-control" name="alternative_signature_content_$index" rows="8"></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" id="alternative-signature-count" name="alternative_signature_count" value="{{count($customSignatures)}}">
        @foreach ($customSignatures as $signature)
            <div class="form-group alternative-signature-group-{{$loop->index}}">
                <input type="hidden" name="alternative_signature_id_{{$loop->index}}" value="{{$signature->id}}">
                <input type="hidden" class="alternative-signature-action" name="alternative_signature_action_{{$loop->index}}" value="KEEP">
                <label for="alternative-signature-{{$loop->index}}" class="col-sm-2 control-label">{{ __('Alternative Email Signature') }} {{$loop->iteration}}<br/>
                    <span class="text-danger delete-alternative-signature" style="cursor: pointer;">{{ __('Delete') }}</span>
                    <span class="text-success restore-alternative-signature hidden" style="cursor: pointer;">{{ __('Restore') }}</span>
                </label>
                <div class="col-md-9 signature-editor">
                    <strong>{{__('Name')}}</strong><br/>
                    <input type="text" class="form-control" name="alternative_signature_title_{{$loop->index}}" value="{{$signature->name}}"><br/>
                    <strong>{{__('Content')}}</strong><br/>
                    <textarea id="alternative-signature-{{$loop->index}}" class="form-control" name="alternative_signature_content_{{$loop->index}}" rows="8">{{$signature->content}}</textarea>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-group">
        <div class="col-md-9 col-md-offset-2">
            <a href="#!/" class="add-alternative-email-signature btn btn-primary">{{__('Add alternative email signature')}}</a>
        </div>
    </div>
@endif

