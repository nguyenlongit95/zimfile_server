<select name="editor_id" id="modal_input_editor" class="form-control" size="10">
    @if(!empty($editors))
        @foreach($editors as $editor)
        <option @if($job->editor_id == $editor->id) selected @endif value="{{ $editor->id }}">{{ $editor->name }} - {{ $editor->email }}</option>
        @endforeach
    @endif
</select>
