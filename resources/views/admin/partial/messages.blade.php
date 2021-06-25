<div style="padding: 20px">
    @if(count($errors))

        @foreach($errors->all() as $error)

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                <strong> Sorry !</strong> {{$error}}
            </div>

        @endforeach

    @endif


    @if(Session::has('success')||Session::has('error'))

        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-{{Session::has('success')?'success':'danger'}}
                alert-dismissible fade show" role="alert">
            <div class="m-alert__icon">
                @if(Session::has('success'))
                    <i class="la la-check"></i>
                @else
                    <i class="flaticon-exclamation-1"></i>
                @endif
                <span></span>
            </div>
            <div class="m-alert__text">

                @if(Session::has('success'))
                    <strong> Done
                        <li class="la la-check"></li>
                    </strong>
                @else
                    <strong> Sorry !</strong>
                @endif
                {!!  Session::has('success')? Session::get('success'):Session::get('error')!!}
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

    @endif

    @if(Session::has('info'))


        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-info alert-dismissible fade show"
             role="alert">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong> Info </strong> {!!  Session::get('info')!!}!
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                </button>
            </div>
        </div>
    @endif
</div>

