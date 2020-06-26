@extends('layouts.master')
@section('content')
<div class="container mt-4">
    <h3 class="mb-4 border-bottom pb-1">Public repos của {{ $userGithub }} | {{ $totalRepo }} repo | <a
            href="{{ route('list-repo') }}">List Repo đã lưu</a></h3>
    <div class="row repo-list">
        @if(count($data)>0)
        @foreach($data as $item)
        <div class="col-sm-4 mb-3 repo-box">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">Id: {{ $item->id }} | <span class="url-repo">{{ $item->html_url }}</span> |
                        star: {{ $item->stargazers_count }} | <a href="javascript:void(0)" class="clone-repo">Clone
                            Repo</a>|<input type="hidden" class="url-fork" value="{{ $item->forks_url }}"></br></p>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @if(count($data)>0)
    <p class="text-center mt-4 mb-5"><button class="load-more btn btn-dark" data-totalResult="{{ $totalRepo }}">Load
            More</button></p>
    @endif
</div>
<script type="text/javascript">
var page = 1;
$(document).ready(function() {
    $(".load-more").on('click', function() {
        var _totalCurrentResult = $(".repo-box").length;
        page++;
        // Ajax Reuqest
        $.ajax({
            url: '{{route("load-more")}}',
            type: 'get',
            dataType: 'json',
            data: {
                count_total_page: _totalCurrentResult,
                user_github: '{{ $userGithub }}',
                page: page,
                _token: '{{csrf_token()}}'
            },
            beforeSend: function() {
                $(".load-more").html('Loading...');
            },
            success: function(response) {
                var _html = '';
                response = JSON.parse(response);
                $.each(response, function(index, value) {
                    _html += '<div class="col-sm-4 mb-3 repo-box">';
                    _html += '<div class="card">';
                    _html += '<div class="card-body">';
                    _html += '<p class="card-text">' + value.id +
                        ' | <span class="url-repo"> ' + value.html_url +
                        ' </span> | ' + value.stargazers_count +
                        ' | <a href="javascript:void(0)" class="clone-repo">Clone Repo</a>| <input type="hidden" class="url-fork" value="' +
                        value.forks_url + '"></p>';
                    _html += '</div>';
                    _html += '</div>';
                    _html += '</div>';
                });
                $(".repo-list").append(_html);
                // Change Load More When No Further result
                var _totalCurrentResult = $(".repo-box").length;
                var _totalResult = parseInt($(".load-more").attr('data-totalResult'));
                if (_totalCurrentResult == _totalResult) {
                    $(".load-more").remove();
                } else {
                    $(".load-more").html('Load More');
                }
            }
        });
    });
});
$("html").on('click', '.clone-repo', function() {
    var _urlRepo = $(this).prev('.url-repo').text();
    var _urlFork = $(this).next('.url-fork').val();
    console.log(_urlRepo);
    $.ajax({
        url: '{{route("clone-repo")}}',
        type: 'post',
        dataType: 'json',
        data: {
            url_repo: _urlRepo,
            url_fork: _urlFork,
            _token: '{{csrf_token()}}'
        },
        success: function(response) {
            if (response.message == 'susscess') {
                alert('Thành công');
            } else {
                alert('Đã được thêm');
            }

        }
    });
});
</script>
@stop