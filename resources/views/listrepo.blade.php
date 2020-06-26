@extends('layouts.master')
@section('content')
<div class="container mt-4">
    <h3 class="mb-4 border-bottom pb-1">List Repo đã save</h3>
    <div class="row repo-list">
        @foreach($listRepo as $item)
        <div class="col-sm-4 mb-3 repo-box">
            <div class="card">
                <div class="card-body">
                    <input type="text" name="name" class="form-control" value="{{ $item->repo }}">
                    @if ($item->fork_status == 1)
                    <button type="submit" class="click-fork btn btn-info pull-right">Fork repo</button>
                    @else
                    <a href="{{ $item->links_forked }}">Links forked</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script type="text/javascript">
$(".click-fork").on('click', '', function() {
    var _urlFork = $(this).prev('.form-control').val();
    console.log(_urlFork);
    $.ajax({
        url: '{{route("fork-repo")}}',
        type: 'post',
        dataType: 'json',
        data: {
            url_fork: _urlFork,
            _token: '{{csrf_token()}}'
        },
        success: function(response) {
            if (response.message == 'susscess') {
                location.reload();
            } else {
                alert('Fail');
            }
        }
    });
});
</script>
@stop