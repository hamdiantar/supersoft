<div class="main-menu customer-side-bar">
    <header class="header">
        <a href="{{route('web:dashboard')}}" class="logo"><img src="{{url('default-images/logo.png')}}" style="width:40px;height:40px;margin:0 10px;border-radius:50%">{{__('Super Car')}}</a>
        <button type="button" class="button-close fa fa-times js__menu_close"></button>


    </header>
    <!-- /.header -->
    <div class="content">

        <div class="navigation">
            <ul class="menu js__accordion">
                <li class="">
                    <a class="waves-effect" href="{{route('web:dashboard')}}">
                        <i style="color:#336BAB !important" class="menu-icon fa fa-dashboard"></i><span>{{__('Dashboard')}}</span></a>
                </li>

                <li>
                    <a class="waves-effect parent-item js__control" href="#"><i style="color:#33AB9B !important" class="menu-icon fa fa-user">
                        </i><span>{{__('Personal data')}}</span><span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">
                        <li class=""><a href="{{route('web:customer.show')}}">{{__('My info')}}</a></li>
                        <li class=""><a href="{{route('web:customer.edit')}}">{{__('Edit My info')}}</a></li>
                    </ul>
                </li>


                <li class="">
                    <a class="waves-effect" href="{{route('web:quotations.index')}}">
                        <i style="color:#FC6654 !important" class="menu-icon fa fa-gift"></i>
                        <span>{{__('Quotations')}}</span>
                    </a>
                </li>

                <li class="">
                    <a class="waves-effect" href="{{route('web:sales.invoices.index')}}">
                        <i style="color:#1e7fd2 !important" class="menu-icon fa fa-file-o"></i>
                        <span>{{__('Sales Invoices')}}</span>
                    </a>
                </li>

                <li class="">
                    <a class="waves-effect" href="{{route('web:sales.invoices.return.index')}}">
                        <i style="color:#5c3773 !important" class="menu-icon fa fa-file-o"></i>
                        <span>{{__('Sales Invoices Return')}}</span>
                    </a>
                </li>

                <li class="">
                    <a class="waves-effect" href="{{route('web:work.cards.index')}}">
                        <i style="color:#3F51B5 !important" class="menu-icon fa fa-car">
                        </i><span>{{__('Car Maintainance')}}</span>
                    </a>
                </li>

                <li class="">
                    <a class="waves-effect" href="{{route('web:reservations.index')}}">
                        <i style="color:#3F51B5 !important" class="menu-icon fa fa-calendar">
                        </i><span>{{__('Services Reservations')}}</span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- /.navigation -->
    </div>
    <!-- /.content -->
</div>
