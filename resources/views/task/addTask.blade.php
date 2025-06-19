@extends("layout.default")

@section("content")
<div class="d-flex align-items-center">
    <div class="container card shadow-sm mt-5" style="max-width: 500px;">
        <div class="fs-3 fw-bold text-center">Add New Task</div>
        <form action="{{route('task.add.post')}}" method="post" class="p-3">
            @csrf
            <div class="mb-3">
                <label for="inputTitle" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="inputTitle" value="{{old('title')}}">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="inputDate" class="form-label">Deadline</label>
                <input name="deadline" type="date" class="form-control" id="inputDate">
                @error('deadline')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="descInput" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="descInput" rows="3"></textarea>
            </div>
            @if(session()->has("success"))
            <div class="alert alert-success">
                {{session()->get("success")}}
            </div>
            @endif
            @if(session()->has("error"))
            <div class="alert alert-danger">
                {{session()->get("error")}}
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <a type="button" onclick="history.go(-1)" class="btn btn-outline-danger rounded-pill">Cancel</a>
                </div>
                <div class="col-md-6 d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-outline-success rounded-pill">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection