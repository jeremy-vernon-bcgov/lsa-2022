@extends('layouts.reports')
@section('content')
<h1>PECSF Donations</h1>
<table class="table table-striped">
  <thead>
    <th>Qualifying Year</th>
    <th>Employee ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Ministry</th>
    <th>Community</th>
    <th>Milestone</th>
    <th>PECSF Pool</th>
    <th>PECSF Region 1</th>
    <th>PECSF Charity 1</th>
    <th>PECSF Charity 1 Vendor Code</th>
    <th>PECSF Region 2</th>
    <th>PECSF Charity 2</th>
    <th>PECSF Charity 2 Vendor Code</th>
  </thead>
  <tbody>
    @foreach($selections as $key => $row)
    @php
      $rowClass = $key%2===0 ? 'even' : 'odd';
    @endphp
    <tr class="{{$rowClass}}">
      <td>{{$row['award_year']}}</td>
      <td>{{$row['employee_number']}}</td>
      <td>{{$row['first_name']}}</td>
      <td>{{$row['last_name']}}</td>
      <td>{{$row['organization']}}</td>
      <td>{{$row['community']}}</td>
      <td>{{$row['milestone']}}</td>
      <td>{{$row['pecsf_pool']}}</td>
      <td>{{$row['pecsf_region_1']}}</td>
      <td>{{$row['pecsf_region_2']}}</td>
      <td>{{$row['pecsf_charity_1']}}</td>
      <td>{{$row['pecsf_charity_1_vendor']}}</td>
      <td>{{$row['pecsf_charity_2']}}</td>
      <td>{{$row['pecsf_charity_2_vendor']}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
