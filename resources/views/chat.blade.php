@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chat with {{$user->name}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="messages">
                        @foreach($messages as $message)
                            <p class="message">
                                {{$message->message}}
                                <br>
                                <span>{{$message->created_at->format('d.m.Y H:i')}}</span>
                            </p>
                        @endforeach
                    </div>

                    <form id="message-form" action="{{url('/chat/submit')}}" method="POST">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="message" class="form-control" name="message" required placeholder="{{__('Write Message')}}"></textarea>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <input type="hidden" name="to_user_id" value="{{$user->id}}">
                        @csrf
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    let nodeHost = '{{env('NODE_HOST')}}';
    let authUserId = '{{$authUser->id}}';
    let userId = '{{$user->id}}';
</script>
