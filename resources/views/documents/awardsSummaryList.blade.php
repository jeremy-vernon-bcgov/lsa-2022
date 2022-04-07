@extends('layouts.reports')
@section('content')
<h1>LSA Awards List and Totals</h1>
<table class="table table-striped">
  <thead>
    <th>Item</th>
    <th>Name</th>
    <th>25</th>
    <th>30</th>
    <th>35</th>
    <th>40</th>
    <th>45</th>
    <th>50</th>
    <th>Extras</th>
  </thead>
  <tbody>
    @foreach($totals as $key => $row)
    @php
      $rowClass = $key%2===0 ? 'even' : 'odd';
    @endphp
    <tr class="{{$rowClass}}">
      <td>{{$key + 1}}</td>
      <td>{{$row['name']}}</td>
      <td>{{$row['25']}}</td>
      <td>{{$row['30']}}</td>
      <td>{{$row['35']}}</td>
      <td>{{$row['40']}}</td>
      <td>{{$row['45']}}</td>
      <td>{{$row['50']}}</td>
      <td>
        @if (isset($row['extras']))
          {{ $row['extras']}}
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
