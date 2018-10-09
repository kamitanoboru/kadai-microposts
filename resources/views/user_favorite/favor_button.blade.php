   @if(Auth::user()->is_favoring($micropost->id)) 
                    {!! Form::open(['route' => ['user.unfavor', $micropost->id], 'method' => 'delete']) !!}
                    {!! Form::submit('Unfavortite', ['class' => 'btn  btn-success btn-xs']) !!}
                    {!! Form::close() !!}
    @else
                    {!! Form::open(['route' => ['user.favor', $micropost->id], 'method' => 'post']) !!}
                        {!! Form::submit('Favortite', ['class' => 'btn btn-default btn-xs']) !!}
                    {!! Form::close() !!}

    @endif