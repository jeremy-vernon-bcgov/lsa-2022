<html>
    <head>
        <title>Report</title>
        <style>
          body, table: {
            font-family: sans-serif;
            width: 100%;
            border: none;
          }
          td, th {
            padding: 0.6em;
            border: none;
          }
          h1 {
            color: #444444;
          }
          .odd {
            background-color: #DDDDDD;
          }
        </style>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
