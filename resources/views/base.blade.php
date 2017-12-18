<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @if (!empty($title))
        <title>{{ $title }}</title>
    @else
        <title>Migration</title>
    @endif
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @if (!empty($next_url))
        <meta http-equiv="refresh" content="1;url={{ $next_url }}">
    @endif
</head>
<body>
    <div class="container">
        <span class="logo">
            <img src="{{ asset('images/miva.png') }}" alt="Miva">
        </span>
        <section class="section">
            @if (!empty($title))
                <h1 class="title">{{ $title }}</h1>
            @else
                <h1 class="title">Migration</h1>
            @endif

            @if (!empty($action))
                <h2 class="action">{{ $action }}</h2>
            @endif

            @if (!empty($next_action))
                <p style="margin-top: 12px; text-align: center;">Next Action: <a href="{{ $next_action }}">{{ $next_action }}</a></p>
            @endif
        </section>

        @if (!empty($progress))
            <section class="section section-progress">
                <p class="progress-lead">
                    <b>Total Progress:</b> <span class="page-number">{{ $progress['page'] }}</span> of <span class="page-number">{{ $progress['total'] }}</span>
                </p>
                <div class="progress-content">
                    <progress value="{{ $progress['page'] }}" max="{{ $progress['total'] }}"></progress>
                    <p class="progress-info">
                        <span class="progress-percent">{{ $progress['percent'] }}%</span> Complete
                    </p>
                </div>
            </section>
        @endif

        @if (!empty($prv_response))
            <section class="section section-prv">
                <h3 class="title">Last PRV Response</h3>
                <pre><code class="language-xml">{{ $prv_response }}</code></pre>
            </section>
        @endif
    </div>
    <script src="{{ asset('js/prism.js') }}"></script>
    <!-- <script src="{{ asset('js/scripts.js') }}"></script> -->
</body>
</html>