<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Start of Social Graph Protocol Meta Data-->
    <meta property="og:locale" content="ru-Ru"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="@yield('sTitleTag',config('app.name'))"/>
    <meta property="og:description" content="@yield('sDescTag','')"/>
    <meta property="og:image" content="@yield('sOGImage',asset('img/site_logo_og.png'))"/>
    <!--End of Social Graph Protocol Meta Data-->

    <title>@yield('sTitleTag',config('app.name'))</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
    {{--<link rel="stylesheet" href="{{mix('/css/styles.min.css')}}">--}}
    @yield('css')

    @php $sAnalHead = \App\Models\Settings::getValue('analitics_code_in_head','') @endphp
    @if(!empty($sAnalHead))
        {!! $sAnalHead !!}
    @endif
</head>
<body>
@php $sAnalBodyStart = \App\Models\Settings::getValue('analitics_code_in_body_start','') @endphp
@if(!empty($sAnalBodyStart))
    {!! $sAnalBodyStart !!}
@endif
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @if (Auth::check())
                    <a href="{{ url('/home') }}">Home</a>

                    @if(Auth::user()->is_admin)
                        <a href="{{route('admin_')}}">Admin Panel</a>
                    @endif

                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i>Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                @else
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                @endif
            </div>
        @endif

        <div class="content">

            @yield('content')

        </div>
    </div>
    @yield('modals')
    {{--<script src="{{mix('/js/scripts.min.js')}}"></script>--}}
    @yield('scripts')
    @php $sAnalBodyEnd = \App\Models\Settings::getValue('analitics_code_in_body_end','') @endphp
    @if(!empty($sAnalBodyEnd))
        {!! $sAnalBodyEnd !!}
    @endif
</body>
</html>
