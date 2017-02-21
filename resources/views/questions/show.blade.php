@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    
    <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
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
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h2>{{ $question->followers_count }}</h2>
                    <span>关注者</span>
                </div>
                <div class="panel-body row">
                    <question-follow-button question="{{ $question->id }}" ></question-follow-button>
                    <a href="#editor" class="btn btn-primary col-md-4 col-md-offset-2">撰写答案</a>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $question->answers_count }}个答案
                </div>
            
                <div class="panel-body">
                    @foreach($question->answers as $answer)
                        <div class="media">
                            <div class="media-left">
                                <a href="">
                                    <img class="img-circle" src="{{ $answer->user->avatar }}" alt="{{ $answer->user->name }}"
                                         width="36">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="meida-heading">
                                    <a href="/user/{{ $answer->user->name }}">{{ $answer->user->name }}</a>
                                </h4>
                                {!! $answer->body !!}
                            </div>
                        </div>
                    @endforeach
                    <div id="editor">
                        @if(Auth::check())
                            <form action="/questions/{{ $question->id }}/answer" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group {{ $errors->has('body') ? 'has-error':'' }}">
                                    <label for="">回复</label>
                                    <script id="container" name="body" type="text/plain" style="height:120px">
                                    </script>
                                    @if($errors->has('body'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                    @endif
                                </div>
            
                                <button class="btn btn-success pull-right" type="submit">回复</button>
                            </form>
                        @else
                            <a class="btn btn-success btn-block" href="{{ url('login') }}">登录提交答案</a>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            toolbars: [
                ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
            ],
            elementPathEnabled: false,
            enableContextMenu: false,
            autoClearEmptyNode:true,
            wordCount:false,
            imagePopup:false,
            autotypeset:{ indent: true,imageBlockLine: 'center' }
        });
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection

@endsection
