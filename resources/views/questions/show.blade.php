@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                   <h2>{{ $question->title }}</h2>
                    {{ $question->author->name }}
                    @foreach($question->topics as $topic)
                        <span class="pull-right topic">{{ $topic->name }}</span>
                    @endforeach
                </div>

                <div class="panel-body">
                    {!! $question->body !!}
                </div>
                <div class="action">
                    @if(Auth::check() && Auth::user()->owns($question))
                        <span class="edit"><a  href="/questions/{{ $question->id
                        }}/edit">编辑问题</a></span>
                        <form action="/questions/{{ $question->id }}" class="del-form" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn-del">删除问题</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> >

@endsection
