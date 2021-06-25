<li>
    <a href="{{route('web:notifications.go.to.link', $notification->id)}}">
        <span class="avatar bg-violet"><i class="fa fa-flag"></i></span>
        <span class="name">{{$notification->data['title']}}</span>
        <span class="desc">{{$notification->data['message']}}</span>
        <span class="time">{{$notification->created_at->diffForHumans()}}</span>
    </a>
</li>
