@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Single Blog Post') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="card" style="width: 18rem;">
                            <img src="..." class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ $post->description }}</p>
                                <a href="{{ route('post.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <form action="{{ route('comments.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input type="hidden" name="post_slug" value="{{ $post->slug }}">
                    <label for="comment" class="form-label">User Comment</label>
                    <input type="text" class="form-control" name="comment" id="comment" aria-describedby="emailHelp">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        @forelse($post->comments as  $comment)
            <div class="row mt-3">
                <div class="comment-container">
                    <h5 class="card-title">
                        @if ($comment->user)
                            <b>User Name : </b>{{ $comment->user->name }}
                        @endif
                    </h5>
                    <p><b>Commented on:</b> {{ $comment->created_at->diffForHumans() }}</p>
                    <p class="card-text"><b>Message : </b> {{ $comment->comment }}</p>
                </div>
                @if (Auth::check() && Auth::id() == $comment->user_id)
                    <div>
                        <button class="btn btn-danger btn-sm" type="button" id="deleteComment" value="{{ $comment->id }}" >Delete</button>
                    </div>
                @endif
            </div>
        @empty
            <div>
                <b>No Comment Yet :</b>
            </div>
        @endforelse

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#deleteComment').click(function(e){
                e.preventDefault();
                if(confirm('Are you sure you want to delete this comment')){
                    var comment_id = $('#deleteComment').val();
                    $.ajax({
                        type: 'POST',
                        url: '/delete-comment',
                        data : {
                            'comment_id' : comment_id
                        },
                        success: function(res){
                            if(res.status == 200){
                                alert(res.message);
                            } else {
                                alert(res.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
