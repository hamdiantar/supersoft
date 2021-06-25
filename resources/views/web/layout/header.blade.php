<div class="fixed-navbar">
    <div class="pull-left">
        <button type="button" class="menu-mobile-button glyphicon glyphicon-menu-hamburger js__menu_mobile"></button>
    @yield('title')
    <!-- /.page-title -->
    </div>

    <!-- /.pull-left -->
    <div class="pull-right">
    <!-- <div class="dropdown">
  <a style="cursor:pointer" class="text-white dropdown-toggle" type="button" data-toggle="dropdown">Language
  <span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li><a href="#">
        <img class="js__toggle_open" src='{{asset("assets/images/lang/en-small.png")}}'
        style="height: 25px !important;"
        data-target="#language-popup">
         English
        </a>
    </li>
    <li><a href="#">
        <img class="js__toggle_open" src='{{asset("assets/images/lang/ar-small.png")}}'
        style="height: 25px !important;"
        data-target="#language-popup">
         عربي
        </a>
    </li>
  </ul>
</div> -->

        <!-- <div class="ico-item">
                    <a href="#" class="ico-item fa fa-search js__toggle_open" data-target="#searchform-header"></a>
                    <form action="#" id="searchform-header" class="searchform js__toggle"><input type="search" placeholder="Search..." class="input-search"><button class="fa fa-search button-search" type="submit"></button></form>

                </div> -->
        <a href="#" class="ico-item">
            @if (App::isLocale('ar'))
                <img class="js__toggle_open" src='{{asset("assets/images/lang/ar-small.png")}}'
                     style="height: 25px !important;"
                     data-target="#language-popup">
            @endif
            @if (App::isLocale('en'))
                <img class="js__toggle_open" src='{{asset("assets/images/lang/en-small.png")}}'
                     style="height: 25px !important;"
                     data-target="#language-popup">
            @endif
        </a>


        <!-- /.ico-item -->
        <div class="ico-item fa fa-arrows-alt js__full_screen hidden-xs"></div>
        <!-- /.ico-item fa fa-fa-arrows-alt -->
        <div class="ico-item toggle-hover js__drop_down ">
            <span class="fa fa-th js__drop_down_button"></span>
            <div class="toggle-content">
                <ul class="ul-wg-head">
                    <li><a href="{{route('web:customer.show')}}" target="_blank"><i class="fa fa-home"></i><span class="txt"> {{__('My info')}}</span></a></li>
                    <li><a href="{{route('web:customer.edit')}}" target="_blank"><i class="fa fa-user"></i><span class="txt">{{__('Edit My info')}}</span></a></li>
                    <li><a href="{{route('web:quotations.index')}}" target="_blank"><i class="fa fa-user"></i><span class="txt">{{__('Quotations')}}</span></a></li>
                    <li><a href="{{route('web:sales.invoices.index')}}" target="_blank"><i class="fa fa-file-text-o"></i><span class="txt">{{__('Sales Invoices')}}</span></a></li>
                    <li><a href="{{route('web:sales.invoices.return.index')}}" target="_blank"><i class="fa fa-file-o"></i><span class="txt">{{__('Sales Invoices Return')}}</span></a></li>
                    <li><a href="{{route('web:work.cards.index')}}" target="_blank"><i class="fa fa-file"></i><span class="txt">{{__('Work Cards')}}</span></a></li>

                </ul>
                <!-- <a href="#" class="read-more">More</a> -->
            </div>
            <!-- /.toggle-content -->
        </div>
        <!-- /.ico-item -->
        <!-- <a href="#" class="ico-item fa fa-envelope notice-alarm js__toggle_open" data-target="#message-popup"></a> -->
        <a href="#" class="ico-item pulse">
            <span class="ico-item fa fa-bell notice-alarm js__toggle_open"
                  data-target="#notification-popup">
            </span>
            <span class="badge js__toggle_open" data-target="#notification-popup" style="background-color: #ea4335" id="notification_count">{{countNotificationsForCustomer()}}</span>
        </a>
        {{--        <a href="#" class="ico-item fa fa-power-off js__logout"></a>--}}
        <a class="dropdown-item ico-item fa fa-power-off " id="log_out"
           onclick="event.preventDefault();">
        </a>

        <form id="logout-form" action="{{ route('web:logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <div class="dropdown drop-wg">
            <button class="btn user-btn-wg dropdown-toggle" type="button" data-toggle="dropdown">
                <ul class="list-inline ul-head">
                    @php
                        $defaultImage = "http://iagonline.org/wp-content/uploads/2016/02/admin.png";
                    @endphp
                    {{--		  <li class="list-inline-item font-weight-bold name-head">{{auth()->gaurd('customer')->user()->username}}</li>--}}
                    <li class="list-inline-item avatar img-head"><img src="{{$defaultImage}}" alt=""></li>
                </ul>
            </button>

            <ul class="dropdown-menu dropdown-menu-wg">
                <li><a href="{{route('web:customer.show')}}"><i class="fa fa-user text-dark"></i> {{__('Profile')}}</a>
                <li><a id="log_out" onclick="event.preventDefault();"><i
                            class="fa fa-sign-out text-dark"></i> {{__('Logout')}}</a>
                </li>
            </ul>
        </div>
    </div>
</div>

</div>
<!-- /.pull-right -->
</div>
<!-- /.fixed-navbar -->

{{-- start choose language--}}
<div id="language-popup" class="notice-popup js__toggle" data-space="75">
    <div class="content">
        <ul class="notice-list">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li>
                    <a rel="alternate" hreflang="{{ $localeCode }}"
                       href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        <img src='{{asset("assets/images/lang/$localeCode.png")}}' style="height: 20px !important;">
                        <span style="">{{$properties['native']}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{--end choose language--}}


<div id="notification-popup" class="notice-popup js__toggle" data-space="75">
    <h2 class="popup-title">{{__('Your notifications')}}</h2>
    <!-- /.popup-title -->
    <div class="content">
        <ul class="notice-list" id="notification_list">

            @if(recentNotificationsForCustomer()->count())
                @foreach(recentNotificationsForCustomer() as $notification)

                    <li>
                        <a href="{{route('web:notifications.go.to.link', $notification->id)}}">
                            <span class="avatar bg-violet"><i class="fa fa-flag"></i></span>
                            <span
                                class="name">{{isset($notification->data['title']) ? $notification->data['title'] : ''}}</span>
                            <span
                                class="desc">{{isset($notification->data['message']) ? $notification->data['message'] : ''}}</span>
                            <span
                                class="time">{{$notification->created_at ? $notification->created_at->diffForHumans() : ''}}</span>
                        </a>
                    </li>

                @endforeach

            @else

                <li style="text-align: center; padding: 10px;" id="no_notification">
                    <span>{{__('No notifications Here')}}</span>
                </li>

            @endif

        </ul>
        <!-- /.notice-list -->
{{--        <a href="#" class="notice-read-more">See more messages <i class="fa fa-angle-down"></i></a>--}}
    </div>
    <!-- /.content -->
</div>
<!-- /#notification-popup -->

<div id="message-popup" class="notice-popup js__toggle" data-space="75">
    <h2 class="popup-title">Recent Messages<a href="#" class="pull-right text-danger">New message</a></h2>
    <!-- /.popup-title -->
    <!-- <div class="content">
        <ul class="notice-list">
            <li>
                <a href="#">
                    <span class="avatar"><img src="http://placehold.it/80x80" alt=""></span>
                    <span class="name">John Doe</span>
                    <span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
                    <span class="time">10 min</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="avatar"><img src="http://placehold.it/80x80" alt=""></span>
                    <span class="name">Harry Halen</span>
                    <span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
                    <span class="time">15 min</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="avatar"><img src="http://placehold.it/80x80" alt=""></span>
                    <span class="name">Thomas Taylor</span>
                    <span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
                    <span class="time">30 min</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="avatar"><img src="http://placehold.it/128x128" alt=""></span>
                    <span class="name">Jennifer</span>
                    <span class="desc">Amet odio neque nobis consequuntur consequatur a quae, impedit facere repellat voluptates.</span>
                    <span class="time">45 min</span>
                </a>
            </li>

        </ul>
        <!-- /.notice-list -->
    <a href="#" class="notice-read-more">See more messages <i class="fa fa-angle-down"></i></a>
</div> -->
<!-- /.content -->
</div>
<!-- /#message-popup -->
<div id="color-switcher">
    <div id="color-switcher-button" class="btn-switcher">
        <div class="inside waves-effect waves-circle waves-light">
            <i class="ico fa fa-gear"></i>
        </div>
        <!-- .inside waves-effect waves-circle -->
    </div>
    <!-- .btn-switcher -->
    <div id="color-switcher-content" class="content">
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "red"]) }}")' data-color="red"
           class="item js__change_color"><span class="color" style="background-color: #f44336;"></span><span
                class="text">Red</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "violet"]) }}")' data-color="violet"
           class="item js__change_color"><span class="color" style="background-color: #673ab7;"></span><span
                class="text">Violet</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "dark-blue"]) }}")'
           data-color="dark-blue" class="item js__change_color"><span class="color"
                                                                      style="background-color: #3f51b5;"></span><span
                class="text">Dark Blue</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "blue"]) }}")' data-color="blue"
           class="item js__change_color active"><span class="color" style="background-color: #304ffe;"></span><span
                class="text">Blue</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "light-blue"]) }}")'
           data-color="light-blue" class="item js__change_color"><span class="color"
                                                                       style="background-color: #2196f3;"></span><span
                class="text">Light Blue</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "green"]) }}")' data-color="green"
           class="item js__change_color"><span class="color" style="background-color: #4caf50;"></span><span
                class="text">Green</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "yellow"]) }}")' data-color="yellow"
           class="item js__change_color"><span class="color" style="background-color: #ffc107;"></span><span
                class="text">Yellow</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "orange"]) }}")' data-color="orange"
           class="item js__change_color"><span class="color" style="background-color: #ff5722;"></span><span
                class="text">Orange</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "chocolate"]) }}")'
           data-color="chocolate" class="item js__change_color"><span class="color"
                                                                      style="background-color: #795548;"></span><span
                class="text">Chocolate</span></a>
        <a href="#" onclick='change_theme("{{ route('customer-color' ,['color' => "dark-green"]) }}")'
           data-color="dark-green" class="item js__change_color"><span class="color"
                                                                       style="background-color: #263238;"></span><span
                class="text">Dark Green</span></a>
        <span id="color-reset" class="btn-restore-default js__restore_default"
              onclick='change_theme("{{ route('customer-color' ,['color' => 'dark-blue']) }}")'>Reset</span>
    </div>
    <!-- /.content -->
</div>
<!-- #color-switcher -->
