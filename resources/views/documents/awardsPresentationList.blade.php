<x-documents.letterSize>

    <p><strong>Long Service Awards - {{$ceremony->scheduled_dateTime}}</strong></p>
    <p><strong>Award Presentation Numbers - by Organization</strong></p>


    @foreach ($organizations as $organization)
        <h2 class="font-size: 18pt">{{$organization->name}}</h2>

        <table class="table">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Award</th>
            </thead>
            <tbody>
        @foreach ($recipients as $recipient)
            @if  ($recipient->organization->id == $organization->id)
            <tr>
                <td>{{$recipient->id}}</td>
                <td>{{$recipient->first_name}} {{$recipient->last_name}}</td>
                <td>
                    <ul>
                    @foreach($recipient->awards as $award)
                            <li>{{$award->short_name}}</li>
                    @endforeach
                    </ul>
                </td>
            </tr>
            @endif;
        @endforeach
            </tbody>
        </table>
    @endforeach

</x-documents.letterSize>
