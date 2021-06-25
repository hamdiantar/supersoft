@if(count($files))
    @foreach ($files as $file)

        <div class="col-md-3" id="file_{{$file->id}}">

            <div style="border: 1px solid #e4e7ea; text-align: center; padding: 10px; margin-bottom: 10px">

                <h6 onclick="removeFile('{{$file->id}}')" class="fa fa-times" style="float: left;"></h6>

                <a href="{{asset('storage/concession_library/' . $library_path. '/' . $file->file_name)}}" target="_blank">
                    @if(in_array( $file->extension, ['gif','jpeg','jpg','png']))

                        <img style="width: 200px;height: 100px;" src="{{asset('storage/concession_library/' . $library_path. '/' . $file->file_name)}}">
                        <hr>
                        <span>{{\Illuminate\Support\Str::limit($file->name, 20)}}</span>
                    @else
                        <img style="width: 200px;height: 100px;" src="{{asset('images/file.png')}}">
                        <hr>
                        <span>{{\Illuminate\Support\Str::limit($file->name, 20)}}</span>
                    @endif
                </a>
            </div>
        </div>

    @endforeach
@else

    <span id="no_files">{{__('No files found')}}</span>
@endif

