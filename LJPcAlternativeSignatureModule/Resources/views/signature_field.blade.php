@if(count($customSignatures)>0)
    <span class="editor-btm-text">{{ __('Signature') }}:</span>
    <select id="selected-signature" name="selected_signature" class="form-control parsley-exclude" data-mailbox_id="{{ $mailbox->id }}">
        <option value="0">{{__('Default')}}</option>
        @foreach($customSignatures as $signature)
            <option value="{{$signature->id}}" @if ((int)$conversation->selected_signature === $signature->id)selected="selected"@endif>{{$signature->name}}</option>
        @endforeach
    </select>
    <small class="note-bottom-div"></small>
@endif
